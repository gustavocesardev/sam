<?php

namespace App\Application\Services;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\LoginException;
use App\Domain\Exceptions\TokenException;
use App\Domain\Repository\UserRepositoryInterface;

use App\Domain\Utils\Status;
use App\Domain\VO\Email;

use App\Infrastructure\Jobs\SendVerifyEmailJob;

use Bus;
use Hash;
use Http;
use URL;

class AuthService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function register(array $data)
    {
        $user = $this->userRepository->store($data);
        $email = new Email($user->getEmailForVerification());

        $verifyEmail = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => $email->getHash(),
        ]);

        
        
        /**
         * TODO : Verificar por que retorna um response sem o data
         * Dispara o job logo após a resposta da API (sem precisar de queue:work)
         */
        Bus::dispatchAfterResponse(new SendVerifyEmailJob($email->get(), [
            'name' => $user->name,
            'verification_url' => $verifyEmail
        ]));
    }

    /**
     * TODO : Criar VIEW para exibir as mensagens de forma mais amigável
     * Summary of verifyEmail
     * @param array $data
     * @return Status
     */
    public function verifyEmail(array $data): Status
    {
        $user = $this->userRepository->find($data['id']);

        if (!hash_equals((string) $data['hash'], sha1($user->getEmailForVerification())))
        {
            return Status::error('Link inválido ou expirado.');
        }

        if ($user->hasVerifiedEmail())
        {
            return Status::success('E-mail já verificado.');
        }

        $user->markEmailAsVerified();
        
        return Status::success('E-mail verificado com sucesso.');
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);
        
        if (!$user || !Hash::check($data['password'], $user->password))
        {
            throw new LoginException(
                ErrorContext::LOGIN,
                 'Credenciais inválidas.', 
                 401
            );
        }
    
        if (!$user->hasVerifiedEmail())
        {
            throw new LoginException(
                ErrorContext::LOGIN, 
                'Verifique seu e-mail antes de fazer login.', 
                403
            );
        }

        return $this->accessToken($data['email'], $data['password']);
    }

    public function accessToken(string $email, string $password)
    {
        $response = Http::asForm()->post(config('app.url').'/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'username'      => $email,
            'password'      => $password,
            'scope'         => '',
        ]);

        if ($response->failed())
        {
            $hint = !empty($response['hint']) ? $response['hint'] : '';

            throw new TokenException(
                ErrorContext::AUTH_ACCESS_TOKEN,
                 "{$response['error_description']} {$hint}",
                $response->status()
            );
        }

        return $response->json();
    }

    public function refreshToken(string $refreshToken)
    {
        $response = Http::asForm()->post(url('/oauth/token'), [
            'grant_type'    => 'refresh_token',
            'client_id'     => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'refresh_token' => $refreshToken,
            'scope'         => '',
        ]);

        if ($response->failed())
        {
            $hint = !empty($response['hint']) ? $response['hint'] : '';

            throw new TokenException(
                ErrorContext::AUTH_REFRESH_TOKEN,
                 "{$response['error_description']} {$hint}",
                $response->status()
            );
        }

        return $response->json();
    }
}
<?php

namespace App\Application\Services;

use App\Application\Contracts\OAuthClientInterface;
use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\LoginException;

use App\Domain\VO\Status;
use App\Domain\VO\Email;

use App\Infrastructure\Jobs\SendVerifyEmailJob;

use Bus;
use Hash;
use URL;

class AuthService
{
    public function __construct(
        private UserService $userService,
        private OAuthClientInterface $oauthClient
    ) {}

    public function register(array $data)
    {
        $this->userService->validarEmail($data['id_instituicao'], $data['email']);

        $user = $this->userService->store($data);
        $email = new Email($user->getEmailForVerification());

        $verifyEmail = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => $email->getHash(),
        ]);
        
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
        $user = $this->userService->find($data['id']);

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
        $user = $this->userService->findByEmail($data['email']);

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
                'Verifique seu e-mail.', 
                403
            );
        }

        return $this->oauthClient->getAccessToken($data['email'], $data['password']);
    }

    public function refreshToken(string $refreshToken)
    {
        return $this->oauthClient->refreshToken($refreshToken);
    }
}
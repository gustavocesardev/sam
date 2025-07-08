<?php

namespace App\Application\Services;

use App\Application\Contracts\Infrastructure\EmailNotifierInterface;
use App\Application\Contracts\Infrastructure\OAuthClientInterface;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\EmailException;
use App\Domain\Exceptions\LoginException;
use App\Domain\Model\User;

class AuthService
{
    public function __construct(
        private UserService $userService,
        private OAuthClientInterface $oauthClient,
        private EmailNotifierInterface $notifier
    ) {}

    public function register(array $data): void
    {
        $this->userService->validarEmail($data['id_instituicao'], $data['email']);
        $user = $this->userService->store($data);

        $this->notifier->enviarVerificacao($user);
    }

    public function verifyEmail(array $data): User
    {
        $user = $this->userService->find($data['id']);

        if (!$user->verificarEmailHash($data['hash']))
        {
            throw new EmailException(
                ErrorContext::EMAIL_VERIFY, 
                'Link inválido ou expirado.'
            );
        }

        if ($user->hasVerifiedEmail())
        {
            throw new EmailException(
                ErrorContext::EMAIL_VERIFY,
                'E-mail já verificado.'
            );
        }

        $user->markEmailAsVerified();
        return $user->reload();
    }

    public function login(array $data)
    {
        $user = $this->userService->findByEmail($data['email']);

        if (!$user || !$user->verificarSenha($data['password']))
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
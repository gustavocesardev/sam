<?php

namespace App\Infrastructure\Services;

use App\Application\Contracts\Infrastructure\EmailNotifierInterface;

use App\Domain\Model\User;
use App\Domain\VO\Email;

use App\Infrastructure\Jobs\SendVerifyEmailJob;

use Bus;
use URL;

class EmailNotifier implements EmailNotifierInterface
{
    public function enviarVerificacao(User $user): void
    {
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
}
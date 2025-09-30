<?php

namespace App\Application\Contracts\Infrastructure;

use App\Domain\Model\User;

interface EmailNotifierInterface
{
    public function enviarVerificacao(User $user): void;
}

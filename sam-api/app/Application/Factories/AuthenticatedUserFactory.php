<?php

namespace App\Application\Factories;

use App\Domain\VO\Auth\AuthenticatedUser;

class AuthenticatedUserFactory
{
     public static function fromAuth(): AuthenticatedUser
    {
        /** @var \App\Domain\Model\User $user */
        $user = auth()->user();

        return new AuthenticatedUser($user);
    }
}
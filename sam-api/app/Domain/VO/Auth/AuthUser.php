<?php

namespace App\Domain\VO\Auth;

use App\Domain\Model\User;

class AuthUser
{
    public function __construct(
        private User $user,
        private Token $token
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): Token
    {
        return $this->token;
    }
}


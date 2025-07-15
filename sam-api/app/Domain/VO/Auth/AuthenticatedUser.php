<?php

namespace App\Domain\VO\Auth;

use App\Domain\Model\User;

class AuthenticatedUser
{
    public function __construct(private User $user) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIdCurso(): int
    {
        return $this->user->id_curso;
    }

    public function getIdInstituicao(): int
    {
        return $this->user->curso->id_instituicao;
    }

    public function id(): int
    {
        return $this->user->id;
    }

    public function email(): string
    {
        return $this->user->email;
    }
}


<?php

namespace App\Domain\Policies;

use App\Domain\Model\User;

class EmailPolicy
{
    public static function emailEmUso(?User $usuarioExistente, ?int $usuarioAtualId = null): bool
    {
        return $usuarioExistente && $usuarioExistente->id !== $usuarioAtualId;
    }

    public static function pertenceDominioInstitucional(string $email, string $dominioInstitucional): bool
    {
        return str_contains($email, $dominioInstitucional);
    }
}

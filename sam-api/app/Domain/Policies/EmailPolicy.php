<?php

namespace App\Domain\Policies;

class EmailPolicy
{
    public static function pertenceDominioInstitucional(string $email, string $dominioInstitucional): bool
    {
        return str_contains($email, $dominioInstitucional);
    }
}

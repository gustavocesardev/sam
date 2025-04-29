<?php

namespace App\Domain\Policies;

use App\Domain\Model\User;

class EmailPolicy
{
    public static function pertenceDominioInstitucional(string $email, string $dominioInstitucional): bool
    {
        return str_contains($email, $dominioInstitucional);
    }
}

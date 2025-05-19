<?php

namespace App\Domain\Policies;

class CursoPolicy
{
    public static function anoFimCursoValido(int $anoInicio, int $anoFim, int $duracaoMaxima): bool
    {
        return ($anoFim - $anoInicio) <= $duracaoMaxima;
    }
}

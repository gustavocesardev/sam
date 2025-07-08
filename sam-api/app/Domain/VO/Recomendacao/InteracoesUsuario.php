<?php

namespace App\Domain\VO\Recomendacao;

use App\Domain\Model\User;
use App\Domain\VO\Recomendacao\Abstract\Interacoes;

class InteracoesUsuario extends Interacoes
{
    public function __construct(
        public User $usuario,
        public array $reacoes,
        public array $visualizacoes
    ) {}

    public function getIdUsuario(): int
    {
        return $this->usuario->id;
    }

    public function getUsuarioIdCurso(): int
    {
        return $this->usuario->curso->id;
    }

    public function getUsuarioIdInstituicao(): int
    {
        return $this->usuario->curso->instituicao->id;
    }
}

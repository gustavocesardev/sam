<?php

namespace App\Domain\VO\Recomendacao;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\VO\Recomendacao\Abstract\Interacoes;

class InteracoesMembro extends Interacoes
{
    public function __construct(
        public Membro $membro,
        public array $reacoes,
        public array $visualizacoes
    ) {}

    public function getMembroIdInstituicao(): int
    {
        return $this->membro->user->curso->instituicao->id;
    }

    public function getMembroIdGrupoEstudo(): int
    {
        return $this->membro->id_grupo_estudo;
    }
}

<?php

namespace App\Domain\VO\Recomendacao;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\VO\Recomendacao\Abstract\Interacoes;

class InteracoesMembro extends Interacoes
{
    public function __construct(
        public Membro $usuario,
        public array $reacoes,
        public array $visualizacoes
    ) {}
}

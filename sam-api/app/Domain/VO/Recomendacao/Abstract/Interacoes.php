<?php

namespace App\Domain\VO\Recomendacao\Abstract;

abstract class Interacoes
{
    public function __construct(
        public array $reacoes,
        public array $visualizacoes
    ) {}

    public function getPublicacoesCurtidasIds(): array
    {
        return array_map(fn($r) => $r->id_publicacao, $this->reacoes);
    }

    public function getPublicacoesVisualizadasIds(): array
    {
        return array_map(fn($v) => $v->id_publicacao, $this->visualizacoes);
    }

    public function getIdsIgnorados(): array
    {
        return array_unique(array_merge(
            $this->getPublicacoesCurtidasIds(),
            $this->getPublicacoesVisualizadasIds()
        ));
    }
}
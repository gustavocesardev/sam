<?php

namespace App\Domain\Services\Recomendacao\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Repository\GrupoEstudo\ReacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\VisualizacaoRepositoryInterface;
use App\Domain\VO\Recomendacao\InteracoesMembro;

class MembroInteracoesService
{
    public function __construct(
        protected ReacaoRepositoryInterface $reacaoRepository,
        protected VisualizacaoRepositoryInterface $visualizacaoRepository
    ) {}

    public function collectInteracoes(Membro $membro): InteracoesMembro
    {
        $reacoes = $this->reacaoRepository->findByMembro($membro->id);
        $visualizacoes = $this->visualizacaoRepository->findByMembro($membro->id);

        return new InteracoesMembro($membro, $reacoes, $visualizacoes);
    }
}
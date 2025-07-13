<?php

namespace App\Domain\Services\Recomendacao\Publicacao;

use App\Domain\Model\User;
use App\Domain\Repository\Publicacao\ReacaoRepositoryInterface;
use App\Domain\Repository\Publicacao\VisualizacaoRepositoryInterface;
use App\Domain\VO\Recomendacao\InteracoesUsuario;

class UserInteracoesService
{
    public function __construct(
        protected ReacaoRepositoryInterface $reacaoRepository,
        protected VisualizacaoRepositoryInterface $visualizacaoRepository
    ) {}

    public function collectInteracoes(User $usuario): InteracoesUsuario
    {
        $reacoes = $this->reacaoRepository->findByUser($usuario->id);
        $visualizacoes = $this->visualizacaoRepository->findByUser($usuario->id);

        return new InteracoesUsuario( $usuario, $reacoes, $visualizacoes);
    }

    public function collectInteracoesByCurso(User $usuario): InteracoesUsuario
    {
        $reacoes = $this->reacaoRepository->findByUserAndCurso($usuario->id, $usuario->curso->id);
        $visualizacoes = $this->visualizacaoRepository->findByUserAndCurso($usuario->id, $usuario->curso->id);

        return new InteracoesUsuario( $usuario, $reacoes, $visualizacoes);
    }
}
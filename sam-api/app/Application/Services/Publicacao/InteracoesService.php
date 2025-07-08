<?php

namespace App\Application\Services\Publicacao;

use App\Application\Contracts\Services\Publicacao\InteracoesPublicacaoServiceInterface;

use App\Domain\Model\Publicacao\Publicacao;
use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\Publicacao\ReacaoRepositoryInterface;
use App\Domain\Repository\Publicacao\VisualizacaoRepositoryInterface;
use App\Domain\VO\Auth\AuthenticatedUser;

class InteracoesService implements InteracoesPublicacaoServiceInterface
{
    public function __construct(
        protected PublicacaoRepositoryInterface $publicacaoRepository,
        protected UserRepositoryInterface $userRepository,
        protected VisualizacaoRepositoryInterface $visualizacaoRepository,
        protected ReacaoRepositoryInterface $reacaoRepository
    ) {}

    public function registrarVisualizacao(Publicacao $publicacao, AuthenticatedUser $user): void
    {
        $this->visualizacaoRepository->store($publicacao->id, $user->id());
        $publicacao->adicionarVisualizacao();
    }

    public function adicionarReacao(int $idPublicacao, AuthenticatedUser $user): void
    {
        $publicacao = $this->publicacaoRepository->find($idPublicacao);
        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndUsuario($publicacao->id, $user->id());

        if ($publicacaoReacao)
        {
            if ($publicacaoReacao->situacao == 'I')
            {
                $publicacaoReacao->ativar();
                $publicacao->adicionarReacao();
            }

            return;
        }

        $this->reacaoRepository->savePublicacaoReacao($publicacao->id, $user->id());
        $publicacao->adicionarReacao();
    }

    public function removerReacao(int $idPublicacao, AuthenticatedUser $user): void
    {
        $publicacao = $this->publicacaoRepository->find($idPublicacao);
        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndUsuario($publicacao->id, $user->id());

        if ($publicacaoReacao)
        {
            if ($publicacaoReacao->situacao == 'A')
            {   
                $publicacaoReacao->inativar();
                $publicacao->removerReacao();
            }
        }
    }
}
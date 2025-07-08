<?php

namespace App\Application\Services\GrupoEstudo;

use App\Application\Contracts\Services\GrupoEstudo\InteracoesPublicacaoServiceInterface;
use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\MembroNotExistsException;
use App\Domain\Model\GrupoEstudo\Publicacao;

use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\PublicacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\ReacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\VisualizacaoRepositoryInterface;
use App\Domain\VO\Auth\AuthenticatedUser;

class InteracoesService implements InteracoesPublicacaoServiceInterface
{
    public function __construct(
        protected MembroRepositoryInterface $membroRepository,
        protected PublicacaoRepositoryInterface $publicacaoRepository,
        protected UserRepositoryInterface $userRepository,
        protected VisualizacaoRepositoryInterface $visualizacaoRepository,
        protected ReacaoRepositoryInterface $reacaoRepository
    ) {}

    public function registrarVisualizacao(Publicacao $publicacao, AuthenticatedUser $user): void
    {
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id(), $publicacao->getIdGrupoEstudo());

        if (!$membro)
        {
            throw new MembroNotExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO);
        }

        $this->visualizacaoRepository->store($publicacao->id, $membro->id);
        $publicacao->adicionarVisualizacao();
    }

    public function adicionarReacao(int $idPublicacao, AuthenticatedUser $user): void
    {
        $publicacao = $this->publicacaoRepository->find($idPublicacao);
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id(), $publicacao->getIdGrupoEstudo());

        if (!$membro)
        {
            throw new MembroNotExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO);
        }

        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndMembro($publicacao->id, $membro->id);

        if ($publicacaoReacao)
        {
            if ($publicacaoReacao->situacao == 'I')
            {
                $publicacaoReacao->ativar();
                $publicacao->adicionarReacao();
            }

            return;
        }

        $this->reacaoRepository->savePublicacaoReacao($publicacao->id, $membro->id);
        $publicacao->adicionarReacao();
    }

    public function removerReacao(int $idPublicacao, AuthenticatedUser $user): void
    {
        $publicacao = $this->publicacaoRepository->find($idPublicacao);
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id(), $publicacao->getIdGrupoEstudo());

        if (!$membro)
        {
            throw new MembroNotExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO);
        }

        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndMembro($publicacao->id, $membro->id);

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
<?php

namespace App\Application\Services\GrupoEstudo;

use App\Application\Contracts\CryptoServiceInterface;
use App\Application\Contracts\ImageProcessorInterface;

use App\Application\Services\Abstract\PublicavelServiceAbstract;
use App\Application\Services\KeywordService;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\MembroNotExistsException;
use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Model\User;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        private MembroRepositoryInterface $membroRepository,
        UserRepositoryInterface $userRepository,
        PublicacaoRepositoryInterface $publicacaoRepository,
        KeywordService $keywordService,
        VisualizacaoRepositoryInterface $visualizacaoRepository,
        ReacaoRepositoryInterface $reacaoRepository,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService
    ) {
        parent::__construct(
            ErrorContext::GRUPO_ESTUDO_PUBLICACAO,
            $userRepository,
            $publicacaoRepository,
            $keywordService,
            $visualizacaoRepository,
            $reacaoRepository,
            $imageProcessor,
            $cryptoService
        );
    }

    protected function registrarVisualizacao(PublicacaoAbstract $publicacao, User $user)
    {
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id, $publicacao->membro->grupoEstudo->id);

        if (!$membro)
        {
            throw new MembroNotExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO);
        }

        $this->visualizacaoRepository->store($publicacao->id, $membro->id);
        $publicacao->adicionarVisualizacao();
    }

    public function adicionarReacao(int $idPublicacao, User $user)
    {
        $publicacao = $this->find($idPublicacao);
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id, $publicacao->membro->grupoEstudo->id);

        if (!$membro)
        {
            throw new MembroNotExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO);
        }

        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndUsuario($publicacao->id, $membro->id);

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

    public function removerReacao(int $idPublicacao, User $user)
    {
        $publicacao = $this->find($idPublicacao);
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id, $publicacao->membro->grupoEstudo->id);

        if (!$membro)
        {
            throw new MembroNotExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO);
        }

        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndUsuario($publicacao->id, $membro->id);

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

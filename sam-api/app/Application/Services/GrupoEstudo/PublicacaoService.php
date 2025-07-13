<?php

namespace App\Application\Services\GrupoEstudo;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;
use App\Application\Services\Abstract\PublicavelServiceAbstract;

use App\Domain\Exceptions\MembroNotExistsException;
use App\Domain\Enums\ErrorContext;
use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Repository\GrupoEstudo\PublicacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Services\Recomendacao\GrupoEstudo\KeywordService;
use App\Domain\VO\Auth\AuthenticatedUser;

use Illuminate\Database\Eloquent\Collection;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        private MembroRepositoryInterface $membroRepository,
        private RecomendacaoService $recomendacaoService,
        PublicacaoRepositoryInterface $publicacaoRepository,
        KeywordService $keywordService,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService
    ) {
        parent::__construct(
            ErrorContext::GRUPO_ESTUDO_PUBLICACAO,
            $publicacaoRepository,
            $keywordService,
            $imageProcessor,
            $cryptoService
        );
    }

    public function listFeedGeral(AuthenticatedUser $user, GrupoEstudo $grupoEstudo, int $limite = 10): Collection
    {
        $membro = $this->membroRepository->findByUsuarioAndGrupo($user->id(), $grupoEstudo->id);

        if (!$membro)
        {
            throw new MembroNotExistsException(
                ErrorContext::GRUPO_ESTUDO_MEMBRO,
                'Não foi possível gerar o feed pois o usuário não é ingressante do grupo solicitado.'
            );
        }

        return $this->recomendacaoService->recomendarFeed($membro, $limite);
    }
}

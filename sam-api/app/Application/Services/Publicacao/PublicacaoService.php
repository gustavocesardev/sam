<?php

namespace App\Application\Services\Publicacao;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;
use App\Application\Services\Abstract\PublicavelServiceAbstract;

use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\Enums\ErrorContext;
use App\Domain\Services\Recomendacao\Publicacao\KeywordService;
use App\Domain\VO\Auth\AuthenticatedUser;

use Illuminate\Database\Eloquent\Collection;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        private RecomendacaoService $recomendacaoService,
        PublicacaoRepositoryInterface $publicacaoRepository,
        KeywordService $keywordService,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService,
    ) {
        parent::__construct(
            ErrorContext::PUBLICACAO,
            $publicacaoRepository,
            $keywordService,
            $imageProcessor,
            $cryptoService
        );
    }

    public function listFeedGeral(AuthenticatedUser $user, int $limite = 10): Collection
    {
        return $this->recomendacaoService->recomendarFeed($user->getUser(), $limite);
    }

    public function listFeedCurso(AuthenticatedUser $user, int $limite = 10): Collection
    {
        return $this->recomendacaoService->recomendarFeedPorCurso($user->getUser(), $limite);
    }
}

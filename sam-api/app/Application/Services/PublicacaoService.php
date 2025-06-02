<?php

namespace App\Application\Services;

use App\Application\Contracts\CryptoServiceInterface;
use App\Application\Contracts\ImageProcessorInterface;
use App\Application\Services\Abstract\PublicavelServiceAbstract;
use App\Application\Services\Recomendacao\RecomendacaoService;

use App\Domain\Model\User;
use App\Domain\Services\KeywordService;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;

use App\Domain\Enums\ErrorContext;

use Illuminate\Database\Eloquent\Collection;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        private RecomendacaoService $recomendacaoService,
        UserRepositoryInterface $userRepository,
        PublicacaoRepositoryInterface $publicacaoRepository,
        KeywordService $keywordService,
        VisualizacaoRepositoryInterface $visualizacaoRepository,
        ReacaoRepositoryInterface $reacaoRepository,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService,
    ) {
        parent::__construct(
            ErrorContext::PUBLICACAO,
            $userRepository,
            $publicacaoRepository,
            $keywordService,
            $visualizacaoRepository,
            $reacaoRepository,
            $imageProcessor,
            $cryptoService
        );
    }

    public function listFeedGeral(User $user, int $limite = 10): Collection
    {
        return $this->recomendacaoService->recomendarFeed($user, $limite);
    }

    public function listFeedCurso(User $user, int $limite = 10): Collection
    {
        return $this->recomendacaoService->recomendarFeedPorCurso($user, $limite);
    }
}

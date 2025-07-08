<?php

namespace App\Application\Services\Publicacao;


use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;
use App\Application\Services\Abstract\PublicavelServiceAbstract;
use App\Application\Services\Recomendacao\RecomendacaoService;

use App\Domain\Model\User;
use App\Domain\Services\KeywordService;
use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\Publicacao\VisualizacaoRepositoryInterface;
use App\Domain\Repository\Publicacao\ReacaoRepositoryInterface;

use App\Domain\Enums\ErrorContext;

use App\Domain\VO\Auth\AuthenticatedUser;
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

    public function listFeedGeral(AuthenticatedUser $user, int $limite = 10): Collection
    {
        return $this->recomendacaoService->recomendarFeed($user->getUser(), $limite);
    }

    public function listFeedCurso(AuthenticatedUser $user, int $limite = 10): Collection
    {
        return $this->recomendacaoService->recomendarFeedPorCurso($user->getUser(), $limite);
    }
}

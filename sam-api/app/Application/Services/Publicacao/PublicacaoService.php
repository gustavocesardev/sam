<?php

namespace App\Application\Services\Publicacao;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;
use App\Application\Services\Abstract\PublicavelServiceAbstract;

use App\Domain\Model\Publicacao\PublicacaoReacao;
use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\Enums\ErrorContext;
use App\Domain\Services\Recomendacao\Publicacao\KeywordService;
use App\Domain\VO\Auth\AuthenticatedUser;

use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoReacaoRepository;
use Illuminate\Support\Collection;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        private RecomendacaoService $recomendacaoService,
        private PublicacaoReacaoRepository $publicacaoReacaoRepository,
        private PublicacaoRepositoryInterface $publicacaoRepositoryEspecifico,
        KeywordService $keywordService,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService,
    ) {
        parent::__construct(
            ErrorContext::PUBLICACAO,
            $publicacaoRepositoryEspecifico,
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

    public function listPublicacoesCurtidas(AuthenticatedUser $user, int $limite = 15, int $page = 1): Collection
    {
        $curtidasUsuario = $this->publicacaoReacaoRepository->searchByUsuario($user->id(), $limite, $page);

        $publicacoes = $curtidasUsuario
                        ->map(fn(PublicacaoReacao $reacao) => $reacao->publicacao)
                        ->filter()
                        ->values();

        return $publicacoes;
    }

    public function listPublicacoesUsuario(AuthenticatedUser $user, int $limite = 15, int $page = 1): Collection
    {
        $publicacoesUsuario = $this->publicacaoRepositoryEspecifico->searchByUsuario($user->id(), $limite, $page);
        return $publicacoesUsuario;
    }
}

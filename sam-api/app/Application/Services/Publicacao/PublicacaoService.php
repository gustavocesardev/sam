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
        $publicacoes = $this->recomendacaoService->recomendarFeed($user->getUser(), $limite);
        return $this->marcarCurtidas($publicacoes, $user->id());
    }

    public function listFeedCurso(AuthenticatedUser $user, int $limite = 10): Collection
    {
        $publicacoes = $this->recomendacaoService->recomendarFeedPorCurso($user->getUser(), $limite);
        return $this->marcarCurtidas($publicacoes, $user->id());
    }

    public function listPublicacoesCurtidas(AuthenticatedUser $user, int $idUsuario, int $limite = 15, int $page = 1): Collection
    {
        $curtidasUsuario = $this->publicacaoReacaoRepository->searchByUsuario($idUsuario, $limite, $page);

        $publicacoes = $curtidasUsuario
                        ->map(fn(PublicacaoReacao $reacao) => $reacao->publicacao)
                        ->filter()
                        ->values();

        return $this->marcarCurtidas($publicacoes, $user->id());
    }

    public function listPublicacoesUsuario(AuthenticatedUser $user, int $idUsuario, int $limite = 15, int $page = 1): Collection
    {
        $publicacoesUsuario = $this->publicacaoRepositoryEspecifico->searchByUsuario($idUsuario, $limite, $page);
        return $this->marcarCurtidas($publicacoesUsuario, $user->id());
    }

    public function listPublicacoesVinculadas(AuthenticatedUser $user, int $idPublicacao, int $limite = 15, int $page = 1): Collection
    {
        $publicacoesVinculadas = $this->publicacaoRepositoryEspecifico->searchVinculadas($idPublicacao, $limite, $page);
        return $this->marcarCurtidas($publicacoesVinculadas, $user->id());
    }

    public function marcarCurtida($publicacao, int $idUsuario)
    {
        $publicacao->curtido = $idUsuario
            ? $this->publicacaoRepositoryEspecifico->hasReacao($idUsuario, $publicacao->id)
            : false;

        return $publicacao;
    }

    public function marcarCurtidas(Collection $publicacoes, int $idUsuario): Collection
    {
        return $publicacoes->map(function ($publicacao) use ($idUsuario) {
            return $this->marcarCurtida($publicacao, $idUsuario);
        });
    }
}

<?php

namespace App\Providers;

use App\Application\Services\Recomendacao\RecomendacaoService;
use App\Domain\Services\Recomendacao\FeedService;
use App\Domain\Services\Recomendacao\RecomendadorPublicacoesService;
use App\Domain\Services\Recomendacao\UserInteracoesService;
use Illuminate\Support\ServiceProvider;

use App\Application\Services\PublicacaoService;
use App\Domain\Services\KeywordService;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;

use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoKeywordRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoReacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoVisualizacaoRepository;

class PublicacaoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(PublicacaoService::class)
            ->needs(PublicacaoRepositoryInterface::class)
            ->give(PublicacaoRepository::class);

        $this->app->when(PublicacaoService::class)
            ->needs(VisualizacaoRepositoryInterface::class)
            ->give(PublicacaoVisualizacaoRepository::class);

        $this->app->when(PublicacaoService::class)
            ->needs(ReacaoRepositoryInterface::class)
            ->give(PublicacaoReacaoRepository::class);

        $this->app->when(PublicacaoService::class)
            ->needs(KeywordService::class)
            ->give(function ($app) {
                return new KeywordService(
                    $app->make(PublicacaoKeywordRepository::class)
                );
            });
            
        $this->app->when(PublicacaoService::class)
            ->needs(RecomendacaoService::class)
            ->give(function ($app) {

                $reacaoRepo = $app->make(PublicacaoReacaoRepository::class);
                $visualizacaoRepo = $app->make(PublicacaoVisualizacaoRepository::class);
                $publicacaoRepo = $app->make(PublicacaoRepository::class);
                $keywordRepo = $app->make(PublicacaoKeywordRepository::class);

                return new RecomendacaoService(
                    new UserInteracoesService($reacaoRepo, $visualizacaoRepo),
                    new KeywordService($keywordRepo),
                    new RecomendadorPublicacoesService($publicacaoRepo),
                    new FeedService($publicacaoRepo)
                );
            });

    }
}

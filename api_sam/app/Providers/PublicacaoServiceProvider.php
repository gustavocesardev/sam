<?php

namespace App\Providers;

use App\Application\Services\KeywordService;
use Illuminate\Support\ServiceProvider;

use App\Application\Services\PublicacaoService;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\KeywordRepositoryInterface;
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
            ->needs(KeywordRepositoryInterface::class)
            ->give(PublicacaoKeywordRepository::class);

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
    }
}

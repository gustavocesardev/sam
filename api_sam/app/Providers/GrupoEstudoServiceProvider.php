<?php

namespace App\Providers;

use App\Domain\Repository\GrupoEstudo\GrupoEstudoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\GrupoEstudoRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\MembroRepository;
use Illuminate\Support\ServiceProvider;

use App\Application\Services\GrupoEstudo\PublicacaoService as GrupoPublicacaoService;
use App\Application\Services\KeywordService;

use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\KeywordRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;

use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoRepository as GrupoPublicacaoRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoKeywordRepository as GrupoPublicacaoKeywordRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoReacaoRepository as GrupoPublicacaoReacaoRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoVisualizacaoRepository as GrupoPublicacaoVisualizacaoRepository;

class GrupoEstudoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MembroRepositoryInterface::class, MembroRepository::class);
        $this->app->bind(GrupoEstudoRepositoryInterface::class, GrupoEstudoRepository::class);

        $this->app->when(GrupoPublicacaoService::class)
            ->needs(PublicacaoRepositoryInterface::class)
            ->give(GrupoPublicacaoRepository::class);

        $this->app->when(GrupoPublicacaoService::class)
            ->needs(KeywordRepositoryInterface::class)
            ->give(GrupoPublicacaoKeywordRepository::class);

        $this->app->when(GrupoPublicacaoService::class)
            ->needs(VisualizacaoRepositoryInterface::class)
            ->give(GrupoPublicacaoVisualizacaoRepository::class);

        $this->app->when(GrupoPublicacaoService::class)
            ->needs(ReacaoRepositoryInterface::class)
            ->give(GrupoPublicacaoReacaoRepository::class);
            
        $this->app->when(GrupoPublicacaoService::class)
            ->needs(KeywordService::class)
            ->give(function ($app) {
                return new KeywordService(
                    $app->make(GrupoPublicacaoKeywordRepository::class)
                );
            });
    }
}

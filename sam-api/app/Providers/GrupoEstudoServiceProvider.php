<?php

namespace App\Providers;

use App\Application\Contracts\Services\GrupoEstudo\InteracoesPublicacaoServiceInterface;
use App\Application\Services\GrupoEstudo\InteracoesService;

use App\Domain\Repository\GrupoEstudo\GrupoEstudoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\KeywordRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\PublicacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\VisualizacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\ReacaoRepositoryInterface;

use App\Infrastructure\Persistence\Repository\GrupoEstudo\GrupoEstudoRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\MembroRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoRepository as GrupoPublicacaoRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoKeywordRepository as GrupoPublicacaoKeywordRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoReacaoRepository as GrupoPublicacaoReacaoRepository;
use App\Infrastructure\Persistence\Repository\GrupoEstudo\PublicacaoVisualizacaoRepository as GrupoPublicacaoVisualizacaoRepository;

use Illuminate\Support\ServiceProvider;

class GrupoEstudoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MembroRepositoryInterface::class, MembroRepository::class);
        $this->app->bind(GrupoEstudoRepositoryInterface::class, GrupoEstudoRepository::class);

        $this->app->bind(PublicacaoRepositoryInterface::class, GrupoPublicacaoRepository::class);
        $this->app->bind(VisualizacaoRepositoryInterface::class, GrupoPublicacaoVisualizacaoRepository::class);
        $this->app->bind(ReacaoRepositoryInterface::class, GrupoPublicacaoReacaoRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, GrupoPublicacaoKeywordRepository::class);
        $this->app->bind(InteracoesPublicacaoServiceInterface::class, InteracoesService::class);
    }
}

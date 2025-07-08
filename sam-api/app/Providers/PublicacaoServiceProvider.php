<?php

namespace App\Providers;

use App\Application\Contracts\Services\Publicacao\InteracoesPublicacaoServiceInterface;
use App\Application\Services\Publicacao\InteracoesService;

use App\Domain\Repository\Publicacao\KeywordRepositoryInterface;
use App\Domain\Repository\Publicacao\PublicacaoRepositoryInterface;
use App\Domain\Repository\Publicacao\VisualizacaoRepositoryInterface;
use App\Domain\Repository\Publicacao\ReacaoRepositoryInterface;

use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoKeywordRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoReacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoVisualizacaoRepository;

use Illuminate\Support\ServiceProvider;

class PublicacaoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PublicacaoRepositoryInterface::class, PublicacaoRepository::class);
        $this->app->bind(VisualizacaoRepositoryInterface::class, PublicacaoVisualizacaoRepository::class);
        $this->app->bind(ReacaoRepositoryInterface::class, PublicacaoReacaoRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, PublicacaoKeywordRepository::class);
        $this->app->bind(InteracoesPublicacaoServiceInterface::class, InteracoesService::class);
    }
}

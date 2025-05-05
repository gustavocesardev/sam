<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

use App\Application\Contracts\ImageProcessorInterface;
use App\Application\Contracts\OAuthClientInterface;
use App\Application\Contracts\CryptoServiceInterface;

use App\Application\Services\CryptoService;
use App\Application\Services\PublicacaoService;

use App\Domain\Repository\InstituicaoRepositoryInterface;
use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\KeywordRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;

use App\Infrastructure\Auth\OAuthPassportClient;

use App\Infrastructure\Persistence\Repository\InstituicaoRepository;
use App\Infrastructure\Persistence\Repository\CursoRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;

use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoKeywordRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoReacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoVisualizacaoRepository;

use App\Infrastructure\Services\ImageProcessor;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InstituicaoRepositoryInterface::class, InstituicaoRepository::class);
        $this->app->bind(CursoRepositoryInterface::class, CursoRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OAuthClientInterface::class, OAuthPassportClient::class);
        $this->app->bind(ImageProcessorInterface::class, ImageProcessor::class);
        $this->app->bind(CryptoServiceInterface::class, CryptoService::class);
        $this->app->bind(PublicacaoRepositoryInterface::class, PublicacaoRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, PublicacaoKeywordRepository::class);
        
        // PublicacaoService
        $this->app->when(PublicacaoService::class)
                  ->needs(KeywordRepositoryInterface::class)
                  ->give(PublicacaoKeywordRepository::class);

        $this->app->when(PublicacaoService::class)
                  ->needs(VisualizacaoRepositoryInterface::class)
                  ->give(PublicacaoVisualizacaoRepository::class);

        $this->app->when(PublicacaoService::class)
                  ->needs(ReacaoRepositoryInterface::class)
                  ->give(PublicacaoReacaoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}

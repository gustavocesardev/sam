<?php

namespace App\Providers;

use App\Domain\Repository\KeywordRepositoryInterface;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoKeywordRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

use App\Application\Contracts\ImageProcessorInterface;
use App\Application\Contracts\OAuthClientInterface;
use App\Application\Contracts\CryptoServiceInterface;

use App\Infrastructure\Auth\OAuthPassportClient;
use App\Infrastructure\Services\ImageProcessorService;
use App\Application\Services\CryptoService;

use App\Domain\Repository\InstituicaoRepositoryInterface;
use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

use App\Infrastructure\Persistence\Repository\InstituicaoRepository;
use App\Infrastructure\Persistence\Repository\CursoRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Serviços globais
        $this->app->bind(InstituicaoRepositoryInterface::class, InstituicaoRepository::class);
        $this->app->bind(CursoRepositoryInterface::class, CursoRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OAuthClientInterface::class, OAuthPassportClient::class);
        $this->app->bind(ImageProcessorInterface::class, ImageProcessorService::class);
        $this->app->bind(CryptoServiceInterface::class, CryptoService::class);
        $this->app->bind(KeywordRepositoryInterface::class, PublicacaoKeywordRepository::class);
    }

    public function boot(): void
    {
        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}

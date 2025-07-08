<?php

namespace App\Providers;

use App\Application\Contracts\Infrastructure\EmailNotifierInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;
use App\Application\Contracts\Infrastructure\OAuthClientInterface;
use App\Application\Contracts\Infrastructure\CryptoServiceInterface;

use App\Infrastructure\Services\CryptoService;

use App\Domain\Repository\Abstract\KeywordRepositoryAbstract;
use App\Domain\Repository\Abstract\PublicacaoRepositoryAbstract;
use App\Domain\Repository\InstituicaoRepositoryInterface;
use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

use App\Infrastructure\Persistence\Repository\InstituicaoRepository;
use App\Infrastructure\Persistence\Repository\CursoRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoRepository;
use App\Infrastructure\Persistence\Repository\Publicacao\PublicacaoKeywordRepository;

use App\Infrastructure\Auth\OAuthPassportClient;
use App\Infrastructure\Services\EmailNotifier;
use App\Infrastructure\Services\ImageProcessorService;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        $this->app->bind(EmailNotifierInterface::class, EmailNotifier::class);
        $this->app->bind(CryptoServiceInterface::class, CryptoService::class);
        $this->app->bind(KeywordRepositoryAbstract::class, PublicacaoKeywordRepository::class);
        $this->app->bind(PublicacaoRepositoryAbstract::class, PublicacaoRepository::class);
    }

    public function boot(): void
    {
        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}

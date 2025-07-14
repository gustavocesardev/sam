<?php

namespace App\Providers;

use App\Application\Contracts\Infrastructure\DocumentProcessorInterface;
use App\Application\Contracts\Infrastructure\EmailNotifierInterface;
use App\Application\Contracts\Infrastructure\FileProcessorInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;
use App\Application\Contracts\Infrastructure\OAuthClientInterface;
use App\Application\Contracts\Infrastructure\CryptoServiceInterface;

use App\Domain\Repository\ArtigoUniversitarioRepositoryInterface;
use App\Domain\Repository\FormularioRepositoryInterface;
use App\Infrastructure\Persistence\Repository\ArtigoUniversitarioRepository;
use App\Infrastructure\Persistence\Repository\FormularioRepository;
use App\Infrastructure\Services\Abstract\FileProcessorService;
use App\Infrastructure\Services\CryptoService;

use App\Domain\Repository\InstituicaoRepositoryInterface;
use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

use App\Infrastructure\Persistence\Repository\InstituicaoRepository;
use App\Infrastructure\Persistence\Repository\CursoRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;

use App\Infrastructure\Auth\OAuthPassportClient;
use App\Infrastructure\Services\DocumentProcessorService;
use App\Infrastructure\Services\EmailNotifier;
use App\Infrastructure\Services\ImageProcessorService;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Dependências globais
        $this->app->bind(InstituicaoRepositoryInterface::class, InstituicaoRepository::class);
        $this->app->bind(CursoRepositoryInterface::class, CursoRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FormularioRepositoryInterface::class, FormularioRepository::class);
        $this->app->bind(ArtigoUniversitarioRepositoryInterface::class, ArtigoUniversitarioRepository::class);

        // Dependências de infraestrutura
        $this->app->bind(OAuthClientInterface::class, OAuthPassportClient::class);
        $this->app->bind(EmailNotifierInterface::class, EmailNotifier::class);
        $this->app->bind(CryptoServiceInterface::class, CryptoService::class);
        
        $this->app->bind(FileProcessorInterface::class, FileProcessorService::class);
        $this->app->bind(ImageProcessorInterface::class, ImageProcessorService::class);
        $this->app->bind(DocumentProcessorInterface::class, DocumentProcessorService::class);
    }

    public function boot(): void
    {
        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}

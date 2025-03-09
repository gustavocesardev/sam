<?php

namespace App\Providers;

use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

use App\Infrastructure\Persistence\Repository\CursoRepository;
use App\Infrastructure\Persistence\Repository\InstituicaoRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;

use App\Domain\Repository\InstituicaoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

use Laravel\Passport\Passport;

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

<?php

namespace App\Providers;

use App\Repositories\Interfaces\LinkRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LinkRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\LinkServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\LinkService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LinkRepositoryInterface::class, LinkRepository::class);
        $this->app->bind(LinkServiceInterface::class, LinkService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

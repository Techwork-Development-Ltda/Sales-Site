<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Contracts\UserModelInterface;
use App\Models\UserModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserModelInterface::class, UserModel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
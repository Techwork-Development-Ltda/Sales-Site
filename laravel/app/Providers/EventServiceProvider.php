<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmailListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmailListener::class,
        ],
    ];

    public function register(): void { }

    public function boot(): void { }
}

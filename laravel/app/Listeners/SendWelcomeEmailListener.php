<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\UserRegistered;
use App\Jobs\SendWelcomeEmailJob;

class SendWelcomeEmailListener
{
    public function __construct() {}

    public function handle(UserRegistered $event): void
    {
        SendWelcomeEmailJob::dispatch(
            $event->email,
            $event->name
        );
    }
}

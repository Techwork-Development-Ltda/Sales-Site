<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\UserRegistered;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\FeatureFlagModel;

class SendWelcomeEmailListener
{
    public function __construct() {}

    public function handle(UserRegistered $event): void
    {

        if (FeatureFlagModel::where('key', 'email_send_enabled')->value('enabled')) {
            return;
        }
        SendWelcomeEmailJob::dispatch(
            $event->email,
            $event->name
        );
    }
}

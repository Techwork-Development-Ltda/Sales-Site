<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;
    public int $timeout = 10;

    public function __construct(
        private string $email,
        private string $name
    ) {
        $this->onQueue('emails');
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(): void
    {

        try {
            Mail::to($this->email)
                ->send(new WelcomeMail($this->name));
        } catch (\Throwable $e) {
            Log::error('Error sending email', [
                'job' => self::class,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
        
    }
}

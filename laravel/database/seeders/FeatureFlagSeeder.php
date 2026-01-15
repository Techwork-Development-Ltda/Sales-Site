<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureFlagModel;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
            FeatureFlagModel::updateOrCreate(
            ['key' => 'email_send_enabled'],
            [
                'enabled' => false,
                'description' => 'Control email sending (event/listener, queues, notifications).',
            ]
        );
    }
}
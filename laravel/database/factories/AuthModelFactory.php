<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AuthModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthModelFactory extends Factory
{
    protected $model = AuthModel::class;

    public function definition()
    {
        $password = bcrypt('123456');
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'remember_token' => Str::random(10),
        ];
    }
}
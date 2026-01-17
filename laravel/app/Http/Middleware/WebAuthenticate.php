<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class WebAuthenticate extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
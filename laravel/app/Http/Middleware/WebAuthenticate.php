<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (!Auth::guard('web')->check()) {
            return redirect('/login')->withErrors(['error' => 'User not authenticated!']);
        }

        return $next($request);
    }
}
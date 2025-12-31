<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebAuthenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        // Verifica se o usuário está autenticado pelo guard 'web'
        if (!Auth::guard('web')->check()) {
            // Redireciona para a página de login se o usuário não estiver autenticado
            return response()->json(['status' => false,'message' => 'NOT AUTHENTICATED.'], 401);
            //return redirect('/login');
        }

        // Permite continuar a requisição
        return $next($request);
    }
}
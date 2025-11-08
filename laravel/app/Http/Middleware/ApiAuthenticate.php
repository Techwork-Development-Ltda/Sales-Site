<?php

namespace App\Http\Middleware;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiAuthenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // Força resposta JSON ao invés de redirecionar
        if (!$request->expectsJson()) {
            abort(response()->json([
                'status' => false,
                'message' => 'Não autorizado. Token ausente ou inválido.',
                'erros' => ['Token inválido.'],
                'dados' => []
            ], 401));
        }
    }
}
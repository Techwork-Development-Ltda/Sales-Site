<?php
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthModel;

class ApiAuthenticate
{
   public function handle($request, Closure $next)
    {
        try {
            // $token = JWTAuth::getToken();
            // dd($token);die;
            // Usar o JWTAuth para autenticar o usuário com base no token
            Auth::shouldUse('api');
            $payload = JWTAuth::parseToken()->getPayload();
            $user = AuthModel::find(1);
            // Se o usuário não for encontrado
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Usuário não autenticado.'], 401);
            }
            // Permite a requisição avançar
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['status' => false, 'message' => 'Token expirado'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => false, 'message' => 'Token inválido'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Token não encontrado'], 401);
        }
    }
}
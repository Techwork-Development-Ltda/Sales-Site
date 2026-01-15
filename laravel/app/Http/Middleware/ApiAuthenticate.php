<?php
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthModel;

class ApiAuthenticate
{
    public function handle($request, Closure $next)
    {
        try {
            Auth::shouldUse('api');
            $token = JWTAuth::getToken(); 
            $payload = JWTAuth::getPayload($token); 
            $userId = $payload->get('sub'); 
            $user = AuthModel::find($userId);

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not authenticated.'], 401);
            }

            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['status' => false, 'message' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => false, 'message' => 'Invalid token'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Token not found'], 401);
        }
    }
}
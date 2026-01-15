<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginRequest;
use App\Models\AuthModel;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        LoginRequest::validate($credentials);

        $user = AuthModel::where('email', $credentials['email'])->first();
        $validPass = $user ? Hash::check($credentials['password'], $user->password) : false;

        if (!$validPass) {
            throw new InvalidCredentialsException('Credênciais invalidas.', ['Email e senha não se verificam']);
        }

        $token = auth('api')->attempt($credentials);
        $user = auth('api')->user();

        return response()->json([
            'status' => true,
            'message' => 'Token gerado com sucesso.',
            'errors' => [],
            'data' => [
                'token' => $token,
                'user' => $user
            ],
            '_links' => [
                'self' => [
                    'href' => url("/api/login"),
                ],
                'logout' => [
                    'href' => url("/api/logout"),
                    'method' => 'POST'
                ],
                'user' => [
                    'href' => url("/user/self"),
                    'method' => 'GET'
                ]
            ]
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'status' => true,
            'message' => 'Logout efetuado',
            'errors' => [],
            'data' => [],
            '_links' => [
                'self' => [
                    'href' => url("/logout"),
                ],
                'login' => [
                    'href' => url("/login"),
                ],
                'user' => [
                    'href' => url("/user/self"),
                ],
            ]
        ]);
    }

    public function checkAuth()
    {
        return response()->json([
            'status' => true,
            'message' => 'Usuario está autenticado.',
            'errors' => [],
            'data' => auth('api')->user(),
            '_links' => [
                'self' => [
                    'href' => url("/user/self"),
                ],
                'logout' => [
                    'href' => url("/api/logout"),
                    'method' => 'POST'
                ]
            ]
        ]);
    }
}
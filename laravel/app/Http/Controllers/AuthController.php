<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Exceptions\CredenciaisInvalidasException;
use App\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        LoginRequest::validate($credentials);

        $user = \App\Models\AuthModel::where('email', $credentials['email'])->first();
        $senhaValida = $user ? Hash::check($credentials['password'], $user->password) : false;

        if (!$token = auth('api')->attempt($credentials)) {
            throw new CredenciaisInvalidasException('Credênciais invalidas.', ['Email e senha não se verificam']);
        }

        $user = auth('api')->user();
        $id = $user->id;

        return response()->json([
            'status' => true,
            'message' => 'Token gerado com sucesso.',
            'erros' => [],
            'dados' => [
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
            'erros' => [],
            'dados' => [],
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

    public function consultarLogin()
    {
        return response()->json([
            'status' => true,
            'message' => 'Usuario encontrado com sucesso.',
            'erros' => [],
            'dados' => auth('api')->user(),
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
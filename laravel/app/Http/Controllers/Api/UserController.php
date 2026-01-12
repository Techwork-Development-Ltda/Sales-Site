<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Services\UserService;
use App\Exceptions\NotImplementedException;

use App\Http\Requests\GetUserByIdRequest;
use App\Http\Requests\InsertUserRequest;
use App\Http\Requests\PutUserByIdRequest;
use App\Http\Requests\DeleteUserByIdRequest;


class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUserById($id, Request $request) : JsonResponse {
        $request->merge(['id' => $id]);
        $credentials = $request->only(['id']);
        GetUserByIdRequest::validate($credentials);
        $response = $this->userService->getUserById($id);
        return response()->json([
            'status' => true,
            'message' => 'Usuario encontrado com sucesso.',
            'erros' => [],
            'dados' => $response,
            '_links' => [
                'self' => [
                    'href' => url("user/$id"),
                ],
                'update' => [
                    'href' => url("user/$id"),
                    'method' => 'PUT'
                ],
                'delete' => [
                    'href' => url("user/$id"),
                    'method' => 'DELETE'
                ],
                'status' => [
                    'href' => url("user/$id"),
                    'method' => 'PATCH'
                ]
            ]
        ]);
    }

    public function putUserById($id, Request $request) : JsonResponse {
        $request->merge(['id' => $id]);
        $credentials = $request->only(['id', 'name', 'email']);
        PutUserByIdRequest::validate($credentials);
        $response = $this->userService->updateUser($credentials);

        return response()->json([
            'status' => true,
            'message' => 'Usuario atualizado com sucesso.',
            'erros' => [],
            'dados' => $response,
            '_links' => [
                'self' => [
                    'href' => url("user/$id"),
                ],
                'get' => [
                    'href' => url("user/$id"),
                    'method' => 'GET'
                ],
                'delete' => [
                    'href' => url("user/$id"),
                    'method' => 'DELETE'
                ],
                'status' => [
                    'href' => url("user/$id"),
                    'method' => 'PATCH'
                ]
            ]
        ]);
    }

    public function deleteUserById($id, Request $request) : JsonResponse {
        $request->merge(['id' => $id]);
        $credentials = $request->only(['id',]);
        DeleteUserByIdRequest::validate($credentials);
        $response = $this->userService->deleteUserById($credentials['id']);
        return response()->json([
            'status' => $response,
            'message' => 'Usuario excluido com sucesso.',
            'erros' => [],
            'dados' => [],
            '_links' => [
                'self' => [
                    'href' => url("user/$id"),
                ]
            ]
        ]);
    }

    public function patchUserById($id, Request $request) : JsonResponse {
        //abort(501);
        throw new NotImplementedException();
    }

    public function insertUser(Request $request) : JsonResponse {
        $credentials = $request->only(['name', 'email', 'password']);
        InsertUserRequest::validate($credentials);
        $response = $this->userService->insertUser($credentials);
        return response()->json([
            'status' => true,
            'message' => 'Usuario adicionado com sucesso.',
            'erros' => [],
            'dados' => $response,
            '_links' => [
                'self' => [
                    'href' => url("user/".$response['id']),
                ],
                'get' => [
                    'href' => url("user/".$response['id']),
                    'method' => 'GET'
                ],
                'update' => [
                    'href' => url("user/".$response['id']),
                    'method' => 'PUT'
                ],
                'delete' => [
                    'href' => url("user/".$response['id']),
                    'method' => 'DELETE'
                ],
                'status' => [
                    'href' => url("user/".$response['id']),
                    'method' => 'PATCH'
                ]
            ]
        ]);
    }



}
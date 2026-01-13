<?php

namespace App\Repository;

use App\Exceptions\ErroDePersistenciaException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Repository\Contracts\UserRepositoryInterface;

use App\Events\UserRegistered;

class UserRepository implements UserRepositoryInterface
{
    public function getUserById(int $id) : array {
        $retorno = [];
        $usuario = [];

        try {
            $usuario = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            throw new ErroDePersistenciaException();
        }
        
        if (!empty($usuario)) {
            $retorno = [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'email' => $usuario->email
            ];
        }

        return $retorno;
    }

    public function getUserByEmail(string $email) : array {
        $retorno = [];
        $usuario = [];
        try {
            $usuario = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('email', $email)
                ->first();
        } catch (\Throwable $th) {
            throw new ErroDePersistenciaException();
        }

        if (!empty($usuario)) {
            $retorno = [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'email' => $usuario->email
            ];
        }

        return $retorno;
    }

    public function insertUser(array $data) : array {
        try {
            $id = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Throwable $th) {
            throw new ErroDePersistenciaException();
        }  

        //event(new UserRegistered($id,$data['email'], $data['name']));
        
        return [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email']
        ];
    }

    public function verifyNewEmailIsAvailable(string $oldEmail, string $newEmail, int $id) : array {
        $retorno = [];
        $usuario = [];

        try {
            $usuario = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('email', $newEmail)
                ->where('id', '<>', $id)
                ->first();
        } catch (\Throwable $e) {
            throw new ErroDePersistenciaException();
        }

        if (!empty($usuario)) {
            $retorno = [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'email' => $usuario->email
            ];
        }

        return $retorno;
    }

    public function updateUser(int $id, string $name, string $email) : bool {
        try {
            $update = DB::table('users')
                ->where('id', $id)
                ->update([
                    'name'       => $name,
                    'email'      => $email,
                    'updated_at' => now(),
                ]);

            if(!$update) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            throw new ErroDePersistenciaException();
        }
    }  
    
    public function deleteUserById(int $id) : bool {
        try {
            $delete = DB::table('users')
                ->where('id', $id)
                ->delete();

            if(!$delete) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            throw new ErroDePersistenciaException();
        }
    }

    public function isAdmin(int $id): bool {
        try {
            $user = DB::table('users')
                ->select('is_admin')
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            throw new ErroDePersistenciaException();
        }

        if ($user && $user->is_admin === 'Y') {
            return true; // Usuário é admin
        }

        return false; // Usuário não é admin
    }
}
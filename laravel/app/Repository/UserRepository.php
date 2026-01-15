<?php

namespace App\Repository;

use App\Exceptions\PersistenceErrorException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Repository\Contracts\UserRepositoryInterface;
use App\Events\UserRegistered;

class UserRepository implements UserRepositoryInterface
{
    public function getUserById(int $id) : array 
    {
        $response = [];
        $user = [];

        try {
            $user = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            throw new PersistenceErrorException();
        }
        
        if (!empty($user)) {
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];
        }

        return $response;
    }

    public function getUserByEmail(string $email) : array 
    {
        $response = [];
        $user = [];

        try {
            $user = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('email', $email)
                ->first();
        } catch (\Throwable $th) {
            throw new PersistenceErrorException();
        }

        if (!empty($user)) {
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];
        }

        return $response;
    }

    public function insertUser(array $data) : array 
    {
        try {
            $id = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Throwable $th) {
            throw new PersistenceErrorException();
        }  

        event(new UserRegistered($id, $data['email'], $data['name']));
        
        return [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email']
        ];
    }

    public function verifyNewEmailIsAvailable(string $oldEmail, string $newEmail, int $id) : array 
    {
        $response = [];
        $user = [];

        try {
            $user = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('email', $newEmail)
                ->where('id', '<>', $id)
                ->first();
        } catch (\Throwable $e) {
            throw new PersistenceErrorException();
        }

        if (!empty($user)) {
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];
        }

        return $response;
    }

    public function updateUser(int $id, string $name, string $email) : bool 
    {
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
            throw new PersistenceErrorException();
        }
    }  
    
    public function deleteUserById(int $id) : bool 
    {
        try {
            $delete = DB::table('users')
                ->where('id', $id)
                ->delete();

            if(!$delete) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            throw new PersistenceErrorException();
        }
    }

    public function isAdmin(int $id): bool 
    {
        try {
            $user = DB::table('users')
                ->select('is_admin')
                ->where('id', $id)
                ->first();
        } catch (\Throwable $th) {
            throw new PersistenceErrorException();
        }

        if ($user && $user->is_admin === 'Y') {
            return true;
        }

        return false;
    }
}
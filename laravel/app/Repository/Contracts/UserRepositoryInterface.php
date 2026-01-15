<?php

namespace App\Repository\Contracts;

interface UserRepositoryInterface
{
    public function getUserById(int $id) : array;
    public function getUserByEmail(string $email) : array;
    public function insertUser(array $data) : array;
    public function verifyNewEmailIsAvailable(string $oldEmail, string $newEmail, int $id) : array;
    public function updateUser(int $id, string $name, string $email) : bool;
    public function deleteUserById(int $id) : bool;
    public function addUserRole(int $id, int $role) : bool;
}
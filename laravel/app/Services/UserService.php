<?php

namespace App\Services;

use App\Exceptions\PersistenceErrorException;
use App\Repository\Contracts\UserRepositoryInterface;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\DuplicatedValueException;

class UserService
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(int $id) : array
    {
        $user = $this->userRepository->getUserById($id);
        if (empty($user)) {
            throw new ResourceNotFoundException("User not found.", ['ID not identified']);
        }
        
        $response = array(
            'id' => (int) $user['id'],
            'name' => (string) $user['name'],
            'email' => (string) $user['email']
        );

        return $response;
    }

    public function insertUser(array $credentials) : array 
    {
        $this->verifyEmailIsAvailable($credentials['email']);
        $addition = $this->userRepository->insertUser($credentials);
        return [
            'id' => $addition['id'],
            'user' => $addition['name'],
            'email' => $addition['email']
        ];
    }

    private function verifyEmailIsAvailable(string $email) : void 
    {
        $user = $this->userRepository->getUserByEmail($email);
        if(!empty($user)) {
            throw new DuplicatedValueException("Duplicate identified.", [
                'Email already registered.'
            ]);
        }
    }

    public function updateUser(array $credentials) : array 
    {
        //$this->checkUserExistById($credentials['id']);
        $this->checkNewUserEmailIsAvailable($credentials['id'], $credentials['email']);
        $update = $this->userRepository->updateUser($credentials['id'], $credentials['name'], $credentials['email']);
        if(!$update) {
            throw new PersistenceErrorException();
        }
        return $credentials;
    }

    private function checkUserExistById(int $id) : void 
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) {
            throw new ResourceNotFoundException("User not found.", ['ID not identified.']);
        }
    }

    private function checkNewUserEmailIsAvailable(int $id, string $newEmail) : void 
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) {
            throw new ResourceNotFoundException("User not found.", ['ID not identified.']);
        }

        if($user['email'] !== $newEmail) {
            $this->verifyNewEmailIsAvailable($user['email'], $newEmail, $id);
        }
    }

    private function verifyNewEmailIsAvailable(string $oldEmail, string $newEmail, $id) : void 
    {
        $user = $this->userRepository->verifyNewEmailIsAvailable($oldEmail, $newEmail, $id);
        if(!empty($user)) {
            throw new DuplicatedValueException("Invalid request.", [
                'Email already registered.'
            ]);
        }
    }

    public function deleteUserById(int $id) : bool
    {
        $this->checkUserExistById($id);
        $excluir = $this->userRepository->deleteUserById($id);
        if(!$excluir) {
            throw new PersistenceErrorException();
        }
        return true;
    }
}

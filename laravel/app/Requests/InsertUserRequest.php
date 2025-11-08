<?php

namespace App\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\ParametrosInvalidosException;
use App\Helpers\Validator;

class InsertUserRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void {
        $name = $credentials['name'] ?? '';
        $email = $credentials['email'] ?? '';
        $password = $credentials['password'] ?? '';

        if(empty($name)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["O campo 'name' é obrigatório."]);
        }

        if(empty($email)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["O campo 'email' é obrigatório."]);
        }

        if(!Validator::isValidEmail($email)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["Email inválido."]);
        }

        if(empty($password)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["O campo 'password' é obrigatório."]);
        }

        if(!Validator::isValidPassword($password)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["A senha deve conter ao menos 1 letra maiúscula, 1 letra minúscula, 1 número, 1 caractere especial e ter entre 6 e 16 caracteres."]);
        }
    }
}

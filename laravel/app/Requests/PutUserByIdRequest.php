<?php

namespace App\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\ParametrosInvalidosException;
use App\Helpers\Validator;

class PutUserByIdRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void {
        $id = $credentials['id'] ?? '';
        $name = $credentials['name'] ?? '';
        $email = $credentials['email'] ?? '';
        
        if(empty($name)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["O campo 'name' é obrigatório."]);
        }

        if(empty($email)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["O campo 'email' é obrigatório."]);
        }

        if(!Validator::isValidEmail($email)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["Email inválido."]);
        }

        if(!Validator::positiveInt($id)) {
            throw new ParametrosInvalidosException("Error Processing Request", ["ID inválido."]);
        }
    }
}

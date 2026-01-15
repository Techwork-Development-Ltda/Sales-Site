<?php

namespace App\Http\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\InvalidParametersException;
use App\Helpers\Validator;

class InsertUserRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void 
    {
        $name = $credentials['name'] ?? '';
        $email = $credentials['email'] ?? '';
        $password = $credentials['password'] ?? '';

        if(empty($name)) {
            throw new InvalidParametersException("Error Processing Request", ["Invalid name parameter"], 400);
        }

        if(empty($email)) {
            throw new InvalidParametersException("Error Processing Request", ["The 'email' field is required."], 400);
        }

        if(!Validator::isValidEmail($email)) {
            throw new InvalidParametersException("Error Processing Request", ["Invalid email parameter"], 400);
        }

        if(empty($password)) {
            throw new InvalidParametersException("Error Processing Request", ["The 'password' field is required."], 400);
        }

        if(!Validator::isValidPassword($password)) {
            throw new InvalidParametersException("Error Processing Request", ["The password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, 1 special character, and be between 6 and 16 characters."], 400);
        }
    }
}

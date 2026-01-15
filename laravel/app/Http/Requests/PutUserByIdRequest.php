<?php

namespace App\Http\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\InvalidParametersException;
use App\Helpers\Validator;

class PutUserByIdRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void 
    {
        $id = $credentials['id'] ?? '';
        $name = $credentials['name'] ?? '';
        $email = $credentials['email'] ?? '';
        
        if(empty($name)) {
            throw new InvalidParametersException("Error Processing Request", ["Invalid name parameter"]);
        }

        if(empty($email)) {
            throw new InvalidParametersException("Error Processing Request", ["The 'email' field is required."]);
        }

        if(!Validator::isValidEmail($email)) {
            throw new InvalidParametersException("Error Processing Request", ["Invalid email parameter"]);
        }

        if(!Validator::positiveInt($id)) {
            throw new InvalidParametersException("Error Processing Request", ["IInvalid id parameter"]);
        }
    }
}

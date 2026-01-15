<?php

namespace App\Http\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\InvalidParametersException;
use App\Helpers\Validator;

class DeleteUserByIdRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void 
    {
        $id = $credentials['id'] ?? 0;

        if(!Validator::positiveInt($id)) {
            throw new InvalidParametersException("Error Processing Request", ['Invalid id parameter'], 400);
        }
    }
}

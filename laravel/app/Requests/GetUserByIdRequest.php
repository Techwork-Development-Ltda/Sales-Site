<?php

namespace App\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\ParametrosInvalidosException;
use App\Helpers\Validator;

class GetUserByIdRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void {
        $id = $credentials['id'] ?? 0;

        if(!Validator::positiveInt($id)) {
            throw new ParametrosInvalidosException("Error Processing Request", ['Id must be higher then 0.']);
        }
    }
}

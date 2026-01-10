<?php

namespace App\Http\Requests;
use App\Contracts\RequestValidationInterface;
use App\Exceptions\ParametrosInvalidosException;
use App\Helpers\Validator;

class LoginRequest implements RequestValidationInterface
{
    public static function validate(array $credentials) : void {
        $email = $credentials['email'] ?? '';
        $senha = $credentials['password'] ?? '';

        if(empty($email)) {
            throw new ParametrosInvalidosException("Error Processing Request", ['Email is required.']);
        }

        if(empty($senha)) {
            throw new ParametrosInvalidosException("Error Processing Request", ['Password is required.']);
        }
    }
}

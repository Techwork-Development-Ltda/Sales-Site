<?php

namespace App\Exceptions;

use Exception;

class RedisConnectionException extends Exception
{
    protected $errors;

    public function __construct(string $message = 'Error connecting to Redis', array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function render($request)
    {
        return response()->json([
            'status' => false,
            'message' => $this->getMessage(),
            'errors' => $this->errors,
            'data' => []
        ], 503);
    }
}
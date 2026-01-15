<?php

namespace App\Exceptions;

use Exception;

class RedisCommandException extends Exception
{
    protected $errors;
    protected $redisError;

    public function __construct(string $message = 'Error connecting to Redis', array $errors = [], ? string $redisError = null)
    {
        parent::__construct($message);
        $this->errors = $errors;
        $this->redisError = $redisError;
    }

    public function getRedisError(): ?string
    {
        return $this->redisError;
    }

    public function render($request)
    {
        $response = [
            'status' => false,
            'message' => $this->getMessage(),
            'errors' => $this->errors,
            'data' => []
        ];

        if ($this->redisError) {
            $response['redis_error'] = $this->redisError;
        }

        return response()->json($response, 400); 
    }
}
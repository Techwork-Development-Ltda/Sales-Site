<?php

namespace App\Exceptions;

use Exception;

class RedisCommandException extends Exception
{
    protected $erros;
    protected $redisError;

    public function __construct(string $message = 'Erro ao executar comando Redis.', array $erros = [], ? string $redisError = null)
    {
        parent::__construct($message);
        $this->erros = $erros;
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
            'erros' => $this->erros,
            'dados' => []
        ];

        // Adiciona o erro do Redis se disponível
        if ($this->redisError) {
            $response['redis_error'] = $this->redisError;
        }

        return response()->json($response, 400); // 400 Bad Request
    }
}
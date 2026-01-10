<?php

namespace App\Exceptions;

use Exception;

class RedisConnectionException extends Exception
{
    protected $erros;

    public function __construct(string $message = 'Falha ao conectar com o Redis. ', array $erros = [])
    {
        parent::__construct($message);
        $this->erros = $erros;
    }

    public function render($request)
    {
        return response()->json([
            'status' => false,
            'message' => $this->getMessage(),
            'erros' => $this->erros,
            'dados' => []
        ], 503); // 503 Service Unavailable
    }
}
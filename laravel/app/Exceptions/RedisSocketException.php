<?php

namespace App\Exceptions;

use Exception;

class RedisSocketException extends Exception
{
    protected $erros;

    public function __construct(string $message = 'Erro no socket do Redis.', array $erros = [])
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
        ], 500); // 500 Internal Server Error
    }
}
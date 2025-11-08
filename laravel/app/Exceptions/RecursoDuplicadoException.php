<?php

namespace App\Exceptions;

use Exception;

class RecursoDuplicadoException extends Exception
{

    protected $erros;

    public function __construct(string $message = 'Duplicidade identificada.', array $erros = [])
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
        ], 409);
    }
}
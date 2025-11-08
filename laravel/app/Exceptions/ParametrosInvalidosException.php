<?php

namespace App\Exceptions;

use Exception;

class ParametrosInvalidosException extends Exception
{
    protected $erros;

    public function __construct(string $message = 'Parâmetros inválidos ou ausentes.', array $erros = [])
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
        ], 422); 
    }
}

?>
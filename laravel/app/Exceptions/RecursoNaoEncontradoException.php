<?php

namespace App\Exceptions;

use Exception;

class RecursoNaoEncontradoException extends Exception
{

    protected $erros;

    public function __construct(string $message = 'Não foram encontramos resultados para sua consulta.', array $erros = [])
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
        ], 404);
    }
}
<?php

namespace App\Exceptions;

use Exception;

class ErroDePersistenciaException  extends Exception
{
    protected $erros;

    public function __construct(string $message = 'Houve um erro inesperado ao realizar conexão com o banco de dados, por favor, entre em contato com o suporte.', array $erros = [])
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
        ], 500);
    }
}

?>
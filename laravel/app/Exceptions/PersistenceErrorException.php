<?php

namespace App\Exceptions;

use Exception;

class PersistenceErrorException  extends Exception
{
    protected $errors;

    public function __construct(string $message = 'Unexpected error. Please contact support.', array $errors = [])
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
        ], 500);
    }
}

?>
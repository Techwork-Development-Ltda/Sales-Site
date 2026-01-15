<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Http;

class InvalidParametersException extends Exception
{
    protected $errors;
    protected $environment;
    protected $redirectionNameWeb;

    public function __construct(string $message = 'Invalid parameters or missing.', array $errors = [], $environment = "API", $redirectionNameWeb = "home")
    {
        parent::__construct($message);
        $this->errors = $errors;
        $this->environment = $environment;
        $this->redirectionNameWeb = $redirectionNameWeb;
    }

    public function render($request)
    {
        if($this->environment == "WEB") {
            return view($this->redirectionNameWeb, [
                'error' => $this->message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $this->getMessage(),
                'errors' => $this->errors,
                'data' => []
            ], 422); 
        }
    }
}

?>
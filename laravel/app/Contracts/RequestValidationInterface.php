<?php

namespace App\Contracts;

interface RequestValidationInterface
{
    /**
     * Valida os dados recebidos
     *
     * @param array $credentials
     * @return void
     * @throws \App\Exceptions\ParametrosInvalidosException
     */
    public static function validate(array $credentials): void;
}
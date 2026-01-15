<?php

namespace App\Helpers;

class Validator
{
    public static function positiveInt(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false && (int) $value > 0;
    }

    public static function isValidPassword(string $password): bool 
    {
        return (bool) preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{6,16}$/', $password);
    }

    public static function isValidEmail($email) : bool 
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $domain = substr(strrchr($email, "@"), 1);
        
        if (!checkdnsrr($domain, 'MX')) {
            return false;
        }
        
        return true;
    }
}

?>
<?php

namespace App\Helpers;

class Validator
{
    public static function positiveInt(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false && (int) $value > 0;
    }

    public static function isValidPassword(string $password): bool {
        return (bool) preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{6,16}$/', $password);
    }

    public static function isValidEmail($email) : bool {
        // Check basic email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Get domain from email
        $domain = substr(strrchr($email, "@"), 1);
        
        // Check if domain has valid MX records
        if (!checkdnsrr($domain, 'MX')) {
            return false;
        }
        
        return true;
    }
}

?>
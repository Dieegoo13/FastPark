<?php

class TokenService
{
    public static function generate()
    {
        return bin2hex(random_bytes(32));
    }

    public static function createCSRF(): string
    {
        $token = self::generate(16);
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function validateCSRF(string $token): bool
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        $isValid = hash_equals($_SESSION['csrf_token'], $token);
        unset($_SESSION['csrf_token']);

        return $isValid;
    }
}

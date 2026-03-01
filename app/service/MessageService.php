<?php

class MessageService
{
    public static function setError(string $message): void
    {
        $_SESSION['flash_error'] = $message;
    }

    public static function getError(): ?string
    {
        $message = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        return $message;
    }

    public static function setSuccess(string $message): void
    {
        $_SESSION['flash_success'] = $message;
    }

    public static function getSuccess(): ?string
    {
        $message = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);
        return $message;
    }
}

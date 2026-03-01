<?php

class MessageService
{
    public static function add($message, $type = 'info')
    {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }

        $_SESSION['messages'][] = ['text' => $message, 'type' => $type];
    }

    public static function get()
    {
        $messages = $_SESSION['messages'] ?? [];
        unset($_SESSION['messages']);
        return $messages;
    }
}
<?php

class Database
{

    private static $instance = null;
    private static $connection = null;

    private function __construct()
    {
        try {
            self::$connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getConnection()
    {
        if (self::$connection === null) {
            self::getInstance();
        }
        return self::$connection;
    }

    // Previne unserialize
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}

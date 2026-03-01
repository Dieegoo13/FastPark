<?php 
define('DB_HOST', 'localhost');
define('DB_NAME', 'fastpark');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações da aplicação
define('APP_NAME', 'FastPark');
define('BASE_URL', 'http://localhost:8080/');
define('APP_ROOT', dirname(__DIR__));
define('APP_PATH', dirname(__DIR__) . '/app');
// Configurações de sessão
define('SESSION_LIFETIME', 3600); // 1 horas

// Configurações do Quiz
define('QUESTIONS_PER_GAME', 10);
define('TIME_PER_QUESTION', 30); // segundos

// Configurações de pontuação
define('POINTS', 5);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
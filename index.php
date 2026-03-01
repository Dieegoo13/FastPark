<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Caminhos principais
require_once __DIR__ . '/config/config.php';      
require_once __DIR__ . '/app/core/Autoloader.php';
Autoloader::init();

// Inicia o roteador
require_once __DIR__ . '/app/core/Router.php';
$router = new Router();

// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';
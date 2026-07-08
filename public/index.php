<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Router;  
use App\Controller\AuthController;

if (!file_exists(__DIR__ . '/../.env')) {
    header('Location: /install.php');
    exit;
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

session_start();


// istanzio router
$router = new Router();

require __DIR__ . '/../routes/web.php';

// ===============================

// avvio il router
$router->dispatch();

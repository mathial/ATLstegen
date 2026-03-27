<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Load .env if APP_ENV is not already defined
if (!isset($_SERVER['APP_ENV']) && !isset($_ENV['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new RuntimeException('The "symfony/dotenv" package is required to load ".env" files.');
    }

    $dotenv = new Dotenv();

    // Use loadEnv if available (Symfony 6+)
    if (method_exists($dotenv, 'loadEnv')) {
        $dotenv->loadEnv(dirname(__DIR__).'/.env');
    } else {
        // fallback for older Symfony versions
        $path = dirname(__DIR__).'/.env';
        if (file_exists($path)) {
            $dotenv->load($path);
        }
    }
}

// Ensure APP_ENV is set
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';

// Ensure APP_DEBUG is set
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? ($_SERVER['APP_ENV'] !== 'prod');
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = filter_var($_SERVER['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
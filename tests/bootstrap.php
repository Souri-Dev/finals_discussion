<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new Dotenv();

$envFile = dirname(__DIR__) . '/.env';

// Only load .env if it exists (local dev only)
if (file_exists($envFile)) {
    $dotenv->bootEnv($envFile);
}

// Safe check for debug mode
if (($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? false) === '1') {
    umask(0000);
}

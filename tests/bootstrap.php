<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// ONLY load .env in dev environment
if (($_SERVER['APP_ENV'] ?? 'prod') !== 'prod') {
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $envFile = dirname(__DIR__) . '/.env';

    if (file_exists($envFile)) {
        $dotenv->bootEnv($envFile);
    }
}

if (($_SERVER['APP_DEBUG'] ?? false) === '1') {
    umask(0000);
}

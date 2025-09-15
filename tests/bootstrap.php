<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$_SERVER['APP_ENV']   = $_SERVER['APP_ENV']   ?? $_ENV['APP_ENV']   ?? 'test';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? '1';

$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile) && class_exists(\Symfony\Component\Dotenv\Dotenv::class)) {
    (new \Symfony\Component\Dotenv\Dotenv())->usePutenv()->bootEnv($envFile, 'test');
}

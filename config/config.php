<?php
// config/config.php

class Config
{
    private static $env = [];
    private static $loaded = false;

    public static function load(?string $path = null): bool
    {

        if (self::$loaded) {
            return true;
        }

        $path = $path ?? __DIR__ . '/../.env';

        if (!file_exists($path)) {
            throw new Exception(".env no encontrado");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value, '"\'');

            self::$env[$name] = $value;
        }

        self::$loaded = true;

        return true;
    }

    public static function env(string $key, $default = null)
    {

        if (!self::$loaded) {
            self::load();
        }

        return self::$env[$key] ?? $default;
    }
}

/*
|--------------------------------------------------------------------------
| Cargar variables
|--------------------------------------------------------------------------
*/

Config::load();

/*
|--------------------------------------------------------------------------
| BASE PATH
|--------------------------------------------------------------------------
*/

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}

/*
|--------------------------------------------------------------------------
| APP URL
|--------------------------------------------------------------------------
*/

if (!defined('APP_URL')) {
    define('APP_URL', Config::env('APP_URL', 'http://localhost/api_educativa'));
}

/*
|--------------------------------------------------------------------------
| Configuración de errores
|--------------------------------------------------------------------------
*/

if (Config::env('APP_ENV') === 'development') {

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {

    error_reporting(0);
    ini_set('display_errors', 0);
}

/*
|--------------------------------------------------------------------------
| SESSION LIFETIME
|--------------------------------------------------------------------------
*/

if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', Config::env('SESSION_LIFETIME', 120));
}

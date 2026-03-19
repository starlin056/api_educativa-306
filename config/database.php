<?php
// config/database.php

require_once __DIR__ . '/config.php';

class Database
{

    private static $connection = null;

    private function __construct() {}

    public static function getInstance()
    {

        if (self::$connection === null) {

            $dsn = "mysql:host=" . Config::env('DB_HOST') .
                ";port=" . Config::env('DB_PORT') .
                ";dbname=" . Config::env('DB_DATABASE') .
                ";charset=utf8mb4";

            $options = [

                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false

            ];

            try {

                self::$connection = new PDO(
                    $dsn,
                    Config::env('DB_USERNAME'),
                    Config::env('DB_PASSWORD'),
                    $options
                );
            } catch (PDOException $e) {

                if (Config::env('APP_ENV') === 'development') {
                    die("Error de conexión: " . $e->getMessage());
                }

                error_log($e->getMessage());
                die("Error interno del servidor");
            }
        }

        return self::$connection;
    }
}

/*
| Helper global
*/

function db()
{
    return Database::getInstance();
}

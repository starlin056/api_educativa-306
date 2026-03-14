<?php
// config/config.php
// Carga segura de variables de entorno
// @phpstan-ignore-file
// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ConstantNotUpperCase

/**
 * Constants loaded from .env file
 * 
 * @global string APP_ENV Environment: development|production
 * @global string APP_NAME Application name
 * @global string APP_URL Base application URL
 * @global string DB_HOST Database host
 * @global string DB_PORT Database port
 * @global string DB_DATABASE Database name
 * @global string DB_USERNAME Database username
 * @global string DB_PASSWORD Database password
 * @global string SESSION_LIFETIME Session timeout in minutes
 * @global string ENCRYPTION_KEY Encryption key for sensitive data
 */

class Config {
    /** @var array<string, string> */
    private static $env = [];
    
    /** @var bool */
    private static $loaded = false;
    
    /**
     * Load environment variables from .env file
     */
    public static function load(?string $path = null): bool {
        if (self::$loaded) {
            return true;
        }
        
        $path = $path ?? __DIR__ . '/../.env';
        
        if (!file_exists($path)) {
            // Load defaults for development if .env missing
            if (self::$env['APP_ENV'] ?? 'development' === 'development') {
                self::setDefaults();
                self::$loaded = true;
                return true;
            }
            // In production, throw error if .env missing
            throw new Exception("Archivo .env no encontrado en: {$path}");
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            // Skip lines without =
            if (strpos($line, '=') === false) {
                continue;
            }
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            // Remove quotes from value
            $value = trim($value, '"\'');
            
            // Define constant only if not already defined
            if (!defined($name)) {
                define($name, $value);
            }
            self::$env[$name] = $value;
        }
        
        self::$loaded = true;
        return true;
    }
    
    /**
     * Set default values for development
     */
    private static function setDefaults(): void {
        $defaults = [
            'APP_ENV' => 'development',
            'APP_NAME' => 'Centro Educativo ISW-306',
            'APP_URL' => 'http://localhost/api_educativa',
            'DB_HOST' => 'localhost',
            'DB_PORT' => '3306',
            'DB_DATABASE' => 'centro_educativo_db',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => '',
            'SESSION_LIFETIME' => '120',
            'ENCRYPTION_KEY' => 'dev_key_change_in_production_32chars!!'
        ];
        
        foreach ($defaults as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
                self::$env[$key] = $value;
            }
        }
    }
    
    /**
     * Get environment variable value
     */
    public static function get(string $key, $default = null) {
        if (!self::$loaded) {
            self::load();
        }
        return self::$env[$key] ?? $default;
    }
    
    /**
     * Alias for get()
     */
    public static function env(string $key, $default = null) {
        return self::get($key, $default);
    }
}

/// Load configuration immediately
Config::load();

// Define BASE_PATH if not already defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}

// Error reporting based on environment
if (Config::env('APP_ENV', 'development') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
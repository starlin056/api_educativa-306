<?php
// config/database.php
// Conexión segura a MySQL usando PDO con patrón Singleton

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATABASE . ";charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lanzar excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch como array asociativo
            PDO::ATTR_EMULATE_PREPARES   => false,                   // Prepared statements reales
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"      // Codificación
        ];
        
        try {
            $this->connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            // En producción, loguear en archivo, no mostrar al usuario
            if (APP_ENV === 'development') {
                throw new Exception("Error de conexión: " . $e->getMessage());
            } else {
                error_log("DB Connection Error: " . $e->getMessage());
                throw new Exception("Error interno del servidor");
            }
        }
    }
    
    // Patrón Singleton: una sola conexión por request
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Método útil para consultas simples
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

// Función helper para obtener la conexión rápidamente
function db() {
    return Database::getInstance()->getConnection();
}
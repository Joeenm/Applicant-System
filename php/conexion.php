<?php
/**
 * Archivo de conexión a las bases de datos
 * Usuario: DeSoftIX
 * Contraseña: proyecto2
 */

class Database {
    private static $instances = [];
    
    // Configuración de las bases de datos
    private const DB_CONFIG = [
        'admin_db' => [
            'host' => 'localhost',
            'dbname' => 'admin_db',
            'charset' => 'utf8mb4'
        ],
        'usuarios_db' => [
            'host' => 'localhost',
            'dbname' => 'usuarios_db',
            'charset' => 'utf8mb4'
        ],
        'academico_db' => [
            'host' => 'localhost',
            'dbname' => 'academico_db',
            'charset' => 'utf8mb4'
        ]
    ];
    
    private function __construct() {}
    
    public static function getInstance($dbName) {
        if (!isset(self::$instances[$dbName])) {
            try {
                $config = self::DB_CONFIG[$dbName];
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false
                ];
                
                self::$instances[$dbName] = new PDO(
                    $dsn,
                    'DeSoftIX',
                    'proyecto2',
                    $options
                );
                
            } catch (PDOException $e) {
                error_log("Error de conexión a {$dbName}: " . $e->getMessage());
                throw new Exception("Error al conectar con la base de datos. Por favor, inténtelo más tarde.");
            }
        }
        return self::$instances[$dbName];
    }
    
    public static function closeConnections() {
        foreach (self::$instances as $instance) {
            $instance = null;
        }
        self::$instances = [];
    }
}

// Función para obtener conexión con manejo de errores
function getDatabaseConnection($dbName) {
    try {
        return Database::getInstance($dbName);
    } catch (Exception $e) {
        // Mostrar error en producción, log completo en desarrollo
        if (ini_get('display_errors')) {
            die("Error de conexión: " . $e->getMessage());
        } else {
            error_log($e->getMessage());
            die("Error en el sistema. Por favor contacte al administrador.");
        }
    }
}

// Ejemplo de uso:
// $adminDB = getDatabaseConnection('admin_db');
// $usuariosDB = getDatabaseConnection('usuarios_db');
// $academicoDB = getDatabaseConnection('academico_db');
?>
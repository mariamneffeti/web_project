<?php
/*
 Database Configuration
 */
 
function get_env_value($key, $default) {
    $val = $_ENV[$key] ?? getenv($key);
    return ($val !== false && $val !== '') ? $val : $default;
}

define('DB_HOST',     get_env_value('DB_HOST',     'localhost'));
define('DB_NAME',     get_env_value('DB_NAME',     'web_project'));
define('DB_USER',     get_env_value('DB_USER',     'root'));
define('DB_PASSWORD', get_env_value('DB_PASSWORD', ''));
define('DB_PORT',     get_env_value('DB_PORT',     '3306'));
define('DB_CHARSET',  get_env_value('DB_CHARSET',  'utf8mb4'));
/**
 * Database Connection Class
 */
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->connection = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            die(json_encode(["error" => "Database connection failed"]));
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper function to get database connection
 */
function getDB() {
    try {
        $db = new PDO(
            "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASSWORD
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
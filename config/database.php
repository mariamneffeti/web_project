<?php
/*
 Database Configuration — reads from environment variables (Railway)
 Falls back to hardcoded values for local development.
 */

define('DB_HOST',    $_ENV['DB_HOST']    ?? getenv('DB_HOST'));
define('DB_NAME',    $_ENV['DB_NAME']    ?? getenv('DB_NAME'));
define('DB_USER',    $_ENV['DB_USER']    ?? getenv('DB_USER'));
define('DB_PASS',    $_ENV['DB_PASS']    ?? getenv('DB_PASS'));
define('DB_PORT',    $_ENV['DB_PORT']    ?? getenv('DB_PORT'));

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
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
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
            DB_PASS
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
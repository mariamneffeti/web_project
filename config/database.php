

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
                putenv(trim($name) . "=" . trim($value));
            }
        }
    }
}
 
if (!function_exists('get_env_value')) {
    function get_env_value($key, $default) {
        $val = $_ENV[$key] ?? getenv($key);
        return ($val !== false && $val !== '' && $val !== null) ? $val : $default;
    }
}
 

if (!defined('DB_HOST')) {
    loadEnv(__DIR__ . '/../.env');
 
    define('DB_HOST',     get_env_value('DB_HOST',     'localhost'));
    define('DB_PORT',     get_env_value('DB_PORT',     '3306'));
    define('DB_USER',     get_env_value('DB_USER',     'root'));
    define('DB_PASSWORD', get_env_value('DB_PASSWORD', ''));
    define('DB_NAME',     get_env_value('DB_NAME',     'web_project'));
    define('DB_CHARSET',  'utf8mb4');
    define('BASE_URL',    '/webproject/web_project/web%20pages/');
}
 

if (!class_exists('Database')) {
    class Database {
        private static $instance = null;
        private $connection;
 
        private function __construct() {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                ];
                $this->connection = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
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
}

if (!function_exists('getDB')) {
    function getDB() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT            => 5,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ];
            return new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            die("Connexion échouée : " . $e->getMessage());
        }
    }
}
?>
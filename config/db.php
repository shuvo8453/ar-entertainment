<?php

/**
 * AR Entertainment - Database Connection Handler
 * 
 * Secure PDO Singleton connection class with prepared statement enforcement,
 * UTF-8MB4 collation, and robust exception handling.
 */

require_once __DIR__ . '/config.php';

class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (ENVIRONMENT === 'development') {
                die('<div style="font-family:sans-serif;padding:20px;background:#fee;border:1px solid #f99;border-radius:6px;max-width:650px;margin:30px auto;">' .
                    '<h3 style="color:#c00;margin-top:0;">Database Connection Error</h3>' .
                    '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>' .
                    '<p><small>Ensure Laragon MySQL service is started and database <code>' . htmlspecialchars(DB_NAME) . '</code> exists.</small></p>' .
                    '</div>');
            } else {
                error_log('Database Connection Error: ' . $e->getMessage());
                die('A database error occurred. Please try again later.');
            }
        }
    }

    /**
     * Get the singleton instance.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection object.
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Quick static accessor to PDO connection.
     */
    public static function pdo(): PDO
    {
        return self::getInstance()->getConnection();
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}

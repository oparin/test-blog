<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO|null        $connection;

    private function __construct()
    {
        $dsn = "mysql:host=" . getenv('DB_HOST') .
            ";dbname=" . getenv('DB_NAME') .
            ";charset=" . getenv('DB_CHARSET');

        try {
            $this->connection = new PDO(
                $dsn,
                getenv('DB_USER'),
                getenv('DB_PASS')
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException(
                'Database connection failed:' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): ?PDO
    {
        return $this->connection;
    }
}

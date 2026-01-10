<?php

namespace App\Models;

use PDO;

class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()?->getConnection();
    }

    public function create($name, $description): int
    {
        $this->db
            ->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)')
            ->execute([
                'name'        => $name,
                'description' => $description,
            ]);

        return $this->db->lastInsertId();
    }

    public function getAll(): array
    {
        return $this->db
            ->query('SELECT * FROM categories')
            ->fetchAll();
    }
}

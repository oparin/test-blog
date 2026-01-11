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
            ->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)")
            ->execute([
                'name'        => $name,
                'description' => $description,
            ]);

        return $this->db->lastInsertId();
    }

    public function getAll(): array
    {
        return $this->db
            ->query("SELECT * FROM categories")
            ->fetchAll();
    }

    public function getAllWithPosts(): array
    {
        $query = "SELECT c.*, (SELECT COUNT(*) FROM posts_categories pc WHERE pc.category_id = c.id) as post_count
                  FROM categories c
                  HAVING post_count > 0
                  ORDER BY c.name";

        $categories = $this->db
            ->query($query)
            ->fetchAll();

        foreach ($categories as &$category) {
            $category['latest_posts'] = $this->getLatestPosts($category['id'], 3);
        }

        return $categories;
    }

    public function getLatestPosts($categoryId, $limit = 3): array
    {
        $query = "SELECT p.* FROM posts p
                  JOIN posts_categories pc ON p.id = pc.post_id
                  WHERE pc.category_id = :category_id
                  ORDER BY p.created_at DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function getPostsByCategory($categoryId, $page = 1, $perPage = 10, $orderBy = 'created_at'): array
    {
        $validOrders = ['created_at', 'views'];
        $orderBy     = in_array($orderBy, $validOrders) ? $orderBy : 'created_at';

        $offset = ($page - 1) * $perPage;

        $query = "SELECT COUNT(*) as total FROM posts p
                  JOIN posts_categories pc ON p.id = pc.post_id
                  WHERE pc.category_id = :category_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['category_id' => $categoryId]);
        $total      = $stmt->fetch()['total'];
        $totalPages = ceil($total / $perPage);

        $query = "SELECT p.* FROM posts p
                  JOIN posts_categories pc ON p.id = pc.post_id
                  WHERE pc.category_id = :category_id
                  ORDER BY p.{$orderBy} DESC
                  LIMIT :offset, :per_page";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        $posts = $stmt->fetchAll();

        return [
            'posts'        => $posts,
            'total_pages'  => $totalPages,
            'current_page' => $page,
            'total'        => $total
        ];
    }
}

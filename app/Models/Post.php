<?php

namespace App\Models;

use PDO;

class Post
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()?->getConnection();
    }

    public function create($title, $description, $content, $image, $views = 0): int
    {
        $this->db
            ->prepare('INSERT INTO posts (title, description, content, image, views) VALUES (:title, :description, :content, :image, :views)')
            ->execute([
                'title'       => $title,
                'description' => $description,
                'content'     => $content,
                'image'       => $image,
                'views'       => $views,
            ]);

        return $this->db->lastInsertId();
    }

    public function assignCategories($postId, $categoryIds): void
    {
        $this->db
            ->prepare('DELETE FROM posts_categories WHERE post_id = :post_id')
            ->execute(['post_id' => $postId]);

        if (empty($categoryIds)) {
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO posts_categories (post_id, category_id) VALUES (:post_id, :category_id)'
        );

        foreach ($categoryIds as $categoryId) {
            $stmt->execute([
                'post_id'     => $postId,
                'category_id' => $categoryId,
            ]);
        }
    }
}

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
            ->prepare("INSERT INTO posts (title, description, content, image, views) VALUES (:title, :description, :content, :image, :views)")
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
            ->prepare("DELETE FROM posts_categories WHERE post_id = :post_id")
            ->execute(['post_id' => $postId]);

        if (empty($categoryIds)) {
            return;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO posts_categories (post_id, category_id) VALUES (:post_id, :category_id)"
        );

        foreach ($categoryIds as $categoryId) {
            $stmt->execute([
                'post_id'     => $postId,
                'category_id' => $categoryId,
            ]);
        }
    }

    private function incrementViews($id): void
    {
        $query = "UPDATE posts SET views = views + 1 WHERE id = :id";
        $this->db
            ->prepare($query)
            ->execute(['id' => $id]);
    }

    public function getPostCategories($postId): array
    {
        $query = "SELECT c.* FROM categories c
                  JOIN posts_categories pc ON c.id = pc.category_id
                  WHERE pc.post_id = :post_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $this->incrementViews($id);

        $query = "SELECT * FROM posts WHERE id = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch();

        if ($post) {
            $post['categories'] = $this->getPostCategories($id);
        }

        return $post;
    }

    public function getSimilarPosts($postId, $categoryIds, $limit = 3): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $ids = implode(',', array_map('intval', $categoryIds));

        $sql = "SELECT DISTINCT p.* FROM posts p
                JOIN posts_categories pc ON pc.post_id = p.id
                WHERE pc.category_id IN ($ids) AND p.id <> $postId
                ORDER BY p.created_at DESC
                LIMIT $limit";

        return $this->db->query($sql)->fetchAll();
    }
}

<?php

namespace App\Seeds;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Database;
use App\Models\Category;
use App\Models\Post;

class Seeder
{
    private \PDO     $db;
    private Category $categoryModel;
    private Post     $postModel;

    public function __construct()
    {
        $this->db            = Database::getInstance()?->getConnection();
        $this->categoryModel = new Category();
        $this->postModel     = new Post();
    }

    public function run()
    {
        echo "Start siding data...\n";

        $this->clearData();

        $this->createCategories();

        $this->createPosts();

        echo "Finished siding data!\n";
    }

    private function clearData(): void
    {
        $this->db->exec("DELETE FROM posts_categories");
        $this->db->exec("DELETE FROM posts");
        $this->db->exec("DELETE FROM categories");
        $this->db->exec("ALTER TABLE categories AUTO_INCREMENT = 1");
        $this->db->exec("ALTER TABLE posts AUTO_INCREMENT = 1");
    }

    private function createCategories(): void
    {
        for ($i = 1; $i < 20; $i++) {
            $this->categoryModel->create('Category - ' . $i, 'Category - ' . $i . ' Description');
        }
    }

    private function createPosts(): void
    {
        $categories = $this->categoryModel->getAll();

        foreach ($categories as $category) {
            for ($i = 1; $i < 7; $i++) {
                $postId = $this->postModel->create(
                    'Post ' . $category['id'] . ' - ' . $i,
                    'Post description ' . $category['id'] . ' - ' . $i,
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vel volutpat nibh, vitae euismod lorem. Suspendisse vulputate ac lacus sed blandit. Maecenas imperdiet, magna id gravida feugiat, dui purus semper sem, id tempor ipsum purus eu odio. Aenean congue sollicitudin ipsum. Quisque felis lectus, venenatis id leo eget, rhoncus tristique erat. Curabitur gravida lectus eu dictum suscipit. Nulla nisi nibh, ornare interdum orci id, condimentum malesuada justo. Aliquam rutrum tincidunt leo in fermentum. Nam mattis odio quis blandit tincidunt. Integer enim dui, consequat a ipsum eget, commodo vehicula justo. Ut suscipit, nisl eu gravida dignissim, nisi metus convallis ex, imperdiet interdum neque orci non nibh. In hac habitasse platea dictumst. Integer volutpat ac nunc in mollis.',
                    'https://picsum.photos/id/' . $category['id'] + $i . '/800/400',
                    random_int(1, 10)
                );

                if (($category['id'] !== 1) && $i % 2 === 0) {
                    $categoryIds = [$category['id'], $category['id'] - 1];
                } else {
                    $categoryIds = [$category['id']];
                }
                $this->postModel->assignCategories($postId, $categoryIds);
            }
        }
    }
}

if (php_sapi_name() === 'cli') {
    $seeder = new Seeder();
    $seeder->run();
}

<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;
use Smarty\Smarty;

class PostController
{
    private Post     $postModel;
    private Category $categoryModel;
    private Smarty   $smarty;

    public function __construct($smarty)
    {
        $this->postModel     = new Post();
        $this->categoryModel = new Category();
        $this->smarty        = $smarty;
    }

    public function show($postId): void
    {
        $post = $this->postModel->getById($postId);

        if (!$post) {
            header("HTTP/1.0 404 Not Found");
            echo "Post not found";
            exit;
        }

        $categoryIds  = array_column($post['categories'], 'id');
        $similarPosts = $this->postModel->getSimilarPosts($postId, $categoryIds, 3);

        $this->smarty->assign('post', $post);
        $this->smarty->assign('similar_posts', $similarPosts);
        $this->smarty->assign('page_title', $post['title']);
        $this->smarty->display('post.tpl');
    }
}

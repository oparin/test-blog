<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;
use Smarty\Smarty;
use App\Exceptions\NotFoundException;

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

    /**
     * @throws NotFoundException
     */
    public function show($postId): void
    {
        $post = $this->postModel->getById($postId);

        if (!$post) {
            throw new NotFoundException('Post not found');
        }

        $categoryIds  = array_column($post['categories'], 'id');
        $similarPosts = $this->postModel->getSimilarPosts($postId, $categoryIds);

        $this->smarty->assign('post', $post);
        $this->smarty->assign('similar_posts', $similarPosts);
        $this->smarty->assign('page_title', $post['title']);
        $this->smarty->display('post.tpl');
    }
}

<?php

namespace App\Controllers;

use App\Models\Category;
use Smarty\Smarty;
use App\Exceptions\NotFoundException;

class CategoryController
{
    private Category $categoryModel;
    private Smarty   $smarty;

    public function __construct($smarty)
    {
        $this->categoryModel = new Category();
        $this->smarty        = $smarty;
    }

    /**
     * @throws NotFoundException
     */
    public function show($categoryId, $page = 1, $orderBy = 'created_at'): void
    {
        $category = $this->categoryModel->getById($categoryId);

        if (!$category) {
            throw new NotFoundException('Category not found');
        }

        $result = $this->categoryModel->getPostsByCategory($categoryId, $page, 3, $orderBy);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('posts', $result['posts']);
        $this->smarty->assign('total_pages', $result['total_pages']);
        $this->smarty->assign('current_page', $result['current_page']);
        $this->smarty->assign('order_by', $orderBy);
        $this->smarty->assign('page_title', $category['name']);
        $this->smarty->display('category.tpl');
    }
}

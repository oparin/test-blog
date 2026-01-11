<?php

namespace App\Controllers;

use App\Models\Category;
use Smarty\Smarty;

class HomeController
{
    private Category $categoryModel;
    private Smarty   $smarty;

    public function __construct($smarty)
    {
        $this->categoryModel = new Category();
        $this->smarty        = $smarty;
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getAllWithPosts();

        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('page_title', 'Home page');
        $this->smarty->display('home.tpl');
    }
}

<?php


namespace Controller\AdminController;

use Core\Controller;


class BlogController extends Controller
{

    public function executeShow()
    {
        $this->render('@admin/posts_list.html.twig');
    }
}
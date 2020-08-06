<?php


namespace Controller\AdminController;

use Core\Controller;


class BlogController extends Controller
{

    public function executeShow()
    {
        $this->render('@admin/posts_list.html.twig');
    }

    public function executeNewPost(){
        $this->render('@admin/post_new.html.twig');
    }

    public function executeEditPost(){
        $this->render('@admin/post_edit.html.twig');
    }
}
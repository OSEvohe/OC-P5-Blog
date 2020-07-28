<?php


namespace Controller\PublicController;

use Core\Controller;


class BlogController extends Controller
{

    public function executeShow()
    {
        $this->render('@public/blog.html.twig');
    }

    public function executeShowPost(){
        $this->render('@public/single-post.html.twig');
    }
}
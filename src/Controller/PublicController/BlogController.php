<?php


namespace Controller\PublicController;

use Controller\PublicController;


class BlogController extends PublicController
{

    public function executeShow(){
        $this->addContent("content", 'blog.html');
        $this->page->display();
    }

    public function executeShowPost(){
        echo "Render Single Post template";
    }
}
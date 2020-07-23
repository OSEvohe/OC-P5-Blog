<?php


namespace Controller\PublicController;


use Controller\PublicController;

class HomeController extends PublicController
{

    public function executeShow(){
        $this->addContent("content", 'index.html');
        $this->page->display();
    }

    public function executeError404(){
        echo "Render Error 404 template";
    }
}
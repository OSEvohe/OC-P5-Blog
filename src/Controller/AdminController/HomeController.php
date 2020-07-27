<?php


namespace Controller\AdminController;

use Controller\AdminController;

class HomeController extends AdminController
{

    public function executeShow(){
        $this->template = $this->twig->load('index.html');
        $this->template->display();
    }

    public function executeError404(){
        echo "Render Error 404 template";
    }
}
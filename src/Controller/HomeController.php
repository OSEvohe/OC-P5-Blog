<?php


namespace Controller;

use Core\Controller;

class HomeController extends Controller
{

    public function executeShow(){
        $this->template = $this->twig->load('index.html');
        $this->template->display();
    }

    public function executeError404(){
        echo "Render Error 404 template";
    }
}
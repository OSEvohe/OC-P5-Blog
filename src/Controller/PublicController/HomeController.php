<?php


namespace Controller\PublicController;


use Controller\PublicController;

class HomeController extends PublicController
{

    public function executeShow(){

        $vars = ["accroche" => "Super phrase de présentation"];
        $this->addContent("content", 'index.html', $vars);
        $this->page->display();
    }

    public function executeError404(){
        echo "Render Error 404 template";
    }
}
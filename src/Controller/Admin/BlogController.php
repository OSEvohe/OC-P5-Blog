<?php


namespace Controller\AdminController;

use Controller\AdminController;


class BlogController extends AdminController
{

    public function executeShow(){
        echo "Render Blog admin template";
    }

    public function executeShowPost(){
        echo "Render Single Post admin template";
    }
}
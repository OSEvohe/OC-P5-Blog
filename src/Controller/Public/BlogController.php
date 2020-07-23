<?php


namespace Controller\PublicController;

use Controller\PublicController;


class BlogController extends PublicController
{

    public function executeShow(){
        echo "Render Blog template";
    }

    public function executeShowPost(){
        echo "Render Single Post template";
    }
}
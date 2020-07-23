<?php


namespace Controller;

use Core\Controller;


class BlogController extends Controller
{

    public function executeShow(){
        echo "Render Blog template";
    }

    public function executeShowPost(){
        echo "Render Single Post template";
    }
}
<?php


namespace Controller;

use Core\Controller;

class HomeController extends Controller
{

    public function executeShow(){
        echo "Render twig template";
    }

    public function executeError404(){
        echo "Render Error 404 template";
    }
}
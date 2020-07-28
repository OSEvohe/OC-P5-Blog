<?php


namespace Controller\PublicController;

use Core\Controller;


class HomeController extends Controller
{
    public function executeShow()
    {
        $this->render('@public/index.html.twig');
    }

    public function executeError404()
    {
        $this->render('@public/error404.html.twig');
    }
}
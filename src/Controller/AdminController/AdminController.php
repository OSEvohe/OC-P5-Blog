<?php


namespace Controller\AdminController;

use Core\Controller;


class AdminController extends Controller
{
    public function executeShow()
    {
        $this->render('@admin/index.html.twig');
    }

    public function executeError404()
    {
        $this->render('@public/error404.html.twig');
    }
}
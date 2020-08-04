<?php


namespace Controller\AdminController;

use Core\Controller;


class ProfilController extends Controller
{
    public function executeShow()
    {
        $this->render('@admin/profil.html.twig');
    }
}
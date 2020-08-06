<?php


namespace Controller\AdminController;

use Core\Controller;


class ProfilController extends Controller
{
    public function executeShow()
    {
        $this->render('@admin/profil.html.twig');
    }

    public function executeShowSocial()
    {
        $this->render('@admin/social.html.twig');
    }
}
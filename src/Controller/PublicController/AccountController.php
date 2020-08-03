<?php


namespace Controller\PublicController;

use Core\Controller;


class AccountController extends Controller
{
    public function executeLogin()
    {
        $this->render('@public/login.html.twig');
    }

    public function executeLogout()
    {
        $this->redirect('/');
    }

    public function executeRegister(){
        $this->render('@public/register.html.twig');
    }


}
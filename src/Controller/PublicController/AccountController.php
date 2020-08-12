<?php


namespace Controller\PublicController;

use Core\Controller;
use Services\SocialGenerator;


class AccountController extends Controller
{
    use SocialGenerator;

    public function executeLogin()
    {
        $this->getSocialNetworks();
        $this->render('@public/login.html.twig');
    }

    public function executeLogout()
    {
        $this->redirect('/');
    }

    public function executeRegister()
    {
        $this->getSocialNetworks();
        $this->render('@public/register.html.twig');
    }


}
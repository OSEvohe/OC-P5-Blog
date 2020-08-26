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

        if ($this->isFormSubmit('loginSubmit')) {
            if ($this->user->connectUser($_POST['login'], $_POST['password'])) {
                if (isset($_SESSION['return'])) {
                    $this->redirect($_SESSION['return']);
                } else {
                    $this->redirect('/');
                }
            }
        }

        $this->render('@public/login.html.twig');
    }

    public function executeLogout()
    {
        $this->user->disconnectUser();
        $this->redirect('/');
    }

    public function executeRegister()
    {
        $this->getSocialNetworks();
        $this->render('@public/register.html.twig');
    }


}
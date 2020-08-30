<?php


namespace Controller\PublicController;

use Core\Controller;
use Entity\User;
use Models\UserManager;
use Services\SocialGenerator;


class AccountController extends Controller
{
    use SocialGenerator;

    public function executeLogin()
    {
        $this->getSocialNetworks();

        if ($this->isFormSubmit('loginSubmit')) {
            if ($this->user->connectUser($_POST['login'], $_POST['password'])) {
                $this->redirect(0);
            } else {
                $this->templateVars['errors']['connect'][] = "Identifiant ou mot de passe invalide";
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
        if ($this->user->isConnected()) {
            $this->redirect('/');
        }
        if ($this->isFormSubmit('registerSubmit')) {
            if ($this->processRegisterForm()){
                $this->redirect('/');
            }
        }

        $this->getSocialNetworks();
        $this->templateVars['user'] = $this->user->getUser();
        $this->render('@public/register.html.twig');
    }

    private function processRegisterForm()
    {
        $this->hydrateWithHashedPassword($this->user->getUser());
        if ($this->user->getUser()->isValid() && $this->checkPassword($_POST['password'], $_POST['passwordConfirm'])) {
            return $this->createNewUser($this->user->getUser());
        }
        $this->addErrors($this->user->getUser()->getConstraintsErrors());
        return false;
    }

    private function checkPassword($password, $passwordConfirm)
    {
        if ($password == $passwordConfirm) {
            if ($passwordErrors = $this->user->checkPasswordErrors($password)) {
                $this->addErrors(['password' => $passwordErrors]);
            } else {
                return true;
            }
        } else {
            $this->addErrors(['password' => ['Le mot de passe ne correspond pas']]);
        }
        return false;
    }

    private function hydrateWithHashedPassword(User $user)
    {
        $user->hydrate([
            'login' => $_POST['login'],
            'passwordHash' => $this->user->hashPassword($_POST['password']),
            'displayName' => $_POST['displayName'],
            'role' => [User::ROLE_MEMBER]
        ]);

        return $user;
    }

    private function createNewUser(User $user)
    {
        if ((new UserManager())->findOneBy(['login' => $user->getLogin()])) {
            $this->addErrors(['login' => ['Cet identifiant existe déjà']]);
        } elseif (((new UserManager())->findOneBy(['displayName' => $user->getDisplayName()]))) {
            $this->addErrors(['displayName' => ['Ce pseudo existe déjà']]);
        } else {
            (new UserManager())->create($user);
            $this->user->connectUser($_POST['login'], $_POST['password']);
            return true;
        }
        return false;
    }

}
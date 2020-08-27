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
                if (isset($_SESSION['auth']['return'])) {
                    $this->redirect($_SESSION['auth']['return']);
                } else {
                    $this->redirect('/');
                }
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
        $user = new User($_POST);

        if ($this->isFormSubmit('registerSubmit')) {
            $user = $this->hydrateWithHashedPassword($user);
            if ($user->isValid()) {
                if ($_POST['password'] == $_POST['passwordConfirm']) {
                    if ($passwordErrors = $this->user->checkPasswordErrors($_POST['password'])) {
                        $this->addErrors(['password' => $passwordErrors]);
                    } else {
                        if ($this->createNewUser($user)) {
                            $this->redirect('/');
                        }
                    }
                } else {
                    $this->addErrors(['password' => ['Le mot de passe ne correspond pas']]);
                }
            }
        }

        $this->addErrors($user->getConstraintsErrors());
        $this->getSocialNetworks();
        $this->templateVars['user'] = $user;
        $this->render('@public/register.html.twig');
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
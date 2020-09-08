<?php


namespace Controller\AdminController;


use Entity\User;
use Models\CommentManager;
use Models\PostManager;
use Models\UserManager;

class UserController extends \Core\Controller
{
    const ADMIN_USERS_PAGE = '/admin/users';


    /**
     * Show the page displaying all registered users
     */
    public function executeShowUsers()
    {
        $this->templateVars['users'] = (new UserManager())->findAll(['dateCreated' => 'DESC']);
        $this->render('@admin/users_list.html.twig');
    }


    /**
     * Show the edit user page
     */
    public function executeEditUser()
    {
        $user = (new UserManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('user_editPasswordSubmit')) {
            $this->processPasswordChange($user, $_POST['password']);
        }

        if ($this->isFormSubmit('user_editSubmit')) {
            $this->processEditUser($user);
        }

        $this->templateVars['user'] = $user->entityToArray();
        $this->render('@admin/user_edit.html.twig');
    }


    /**
     * Show the delete user page
     */
    public function executeDeleteUser()
    {
        $user = (new UserManager())->findOneBy(['id' => $this->params['id']]);

        /* Admin cannot be deleted */
        if ($user->getId() == '1' || $this->isFormSubmit('user_deleteCancel')) {
            $this->redirect(self::ADMIN_USERS_PAGE);
        }

        if ($this->isFormSubmit('user_deleteSubmit')) {
            (new UserManager())->delete($user);
            $this->redirect(self::ADMIN_USERS_PAGE);
        }

        $this->templateVars['user'] = $user;
        $this->templateVars['comments_owned'] = (new CommentManager())->findBy(['userId' => $this->params['id']]);
        $this->templateVars['posts_owned'] = (new PostManager())->findBy(['userId' => $this->params['id']]);
        $this->render('@admin/user_delete.html.twig');
    }


    /**
     * Process the Password change form
     * @param User $user
     * @param string $password
     */
    private function processPasswordChange(User $user, string $password)
    {
        if ($error = $this->user->checkPasswordErrors($password)) {
            $this->addFormErrors(['password' => $error]);
        } else {
            $user->setPasswordHash($this->user->hashPassword($password));
            if ($user->isValid()) {
                (new UserManager())->update($user);
                $this->redirect(self::ADMIN_USERS_PAGE);
            }
        }
    }


    /**
     * Check if the user displayName is already in use <br />
     * Return True if another user is using $displayName <br />
     * @param User $user
     * @return bool
     */
    private function isDisplayNameDuplicate(User $user)
    {
        $foundUser = (new UserManager())->findOneBy(["displayName" => $user->getDisplayName()]);

        if (empty($foundUser) || $foundUser->getId() == $user->getId()) {
            return false;
        }

        $this->addFormErrors(['displayName' => ['Ce pseudo existe déjà']]);
        return true;

    }

    /**
     * @param User $user
     */
    private function processEditUser(User $user): void
    {
        $user->hydrate($_POST);

        if ($user->isValid() && !$this->isDisplayNameDuplicate($user)) {
            (new UserManager())->update($user);
            $this->redirect(self::ADMIN_USERS_PAGE);
        } else {
            $this->addFormErrors($user->getConstraintsErrors());
        }
    }

}
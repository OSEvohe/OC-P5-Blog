<?php


namespace Models;


use Core\Entity;

class UserManager extends \Core\Manager
{


    /**
     * Return a list of users having $role role
     * $role an array of role (see USER entity constants
     * @param array $role
     * @return array
     */
    public function findByRole(array $role)
    {
        $usersWithRole = [];
        $users = $this->findAll();
        foreach ($users as $user) {
            foreach ($user->getRole() as $userRole) {
                if (in_array($userRole, $role)) {
                    $usersWithRole[] = $user;
                    break;
                }
            }
        }
        return $usersWithRole;
    }


    /**
     * Delete an user, original admin account (id #1) cannot be deleted
     * @param Entity $user
     */
    public function delete(Entity $user)
    {
        /* Cannot delete Admin #1 account */
        if ($user->getId() != 1) {
            parent::delete($user);
        }

    }

}
<?php


namespace Models;


use Core\Entity;

class UserManager extends \Core\Manager
{


    /**
     * Return a list of users having $role role
     * $role may be a string or an array
     * @param array|string $role
     * @return array
     */
    public function findByRole($role)
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

    public function delete(Entity $user)
    {
        /* Cannot delete Admin #1 account */
        if ($user->getId() != 1) {
            parent::delete($user);
        }

    }

}
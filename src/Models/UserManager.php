<?php


namespace Models;


use Core\Entity;

class UserManager extends \Core\Manager
{


    /**
     * Return a list of users having $role role
     * @param string $role
     * @return array
     */
    public function findByRole(string $role)
    {
        $usersWithRole = [];
        foreach ($this->findAll() as $user) {
            if ($user->hasRole($role)) {
                $usersWithRole[] = $user;
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
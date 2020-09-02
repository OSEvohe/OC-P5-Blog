<?php


namespace Models;


class UserManager extends \Core\Manager
{


    /**
     * Return a list of users having $role role
     * @param string $role
     * @return array
     */
    public function findByRole(string $role){
        $usersWithRole = [];
        $users = $this->findAll();
        foreach ($users as $user){
            if (array_search($role,$user->getRole()) !== FALSE){
                $usersWithRole[] = $user;
            }
        }
        return $usersWithRole;
    }

}
<?php


namespace Models;


class UserManager extends \Core\Manager
{

    public function findByRole(string $role){
        $userWithRole = [];
        $users = $this->findAll("user");
        foreach ($users as $user){
            if (array_search($role,$user->getRole()) !== FALSE){
                $userWithRole[] = $user;
            }
        }
        return $userWithRole;
    }

}
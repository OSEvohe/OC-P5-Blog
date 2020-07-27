<?php

namespace Core;


class Router
{
    protected $zone;
    protected $controller;


    public function __construct()
    {
        $this->setZone();
        $this->setController();
    }

    private function setZone()
    {
        if (isset($_GET['zone']) && $_GET['zone'] == 'Admin') {
            $this->zone = "Admin";
        }  else
            $this->zone = "Public";
    }

    private function setController(){
        if ($this->zone == "Public") {
            $this->setPublicController();
        } else {
            $this->setAdminController();
        }
    }

    private function setPublicController(){
        if (isset($_GET['action'])){
            if ($_GET['action'] == 'home'){
                $this->controller = new \Controller\PublicController\HomeController('show','');
            }
            elseif ($_GET['action'] == 'blog'){
                    $this->controller = new \Controller\PublicController\BlogController('show', '');
            }
            elseif ($_GET['action'] == 'showPost'){
                $this->controller = new \Controller\PublicController\BlogController('showPost', $_GET['id']);
            }
        }
    }

    private function setAdminController(){

    }

    public function getController()
    {
        return $this->controller;
    }
}
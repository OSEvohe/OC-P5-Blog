<?php

namespace Core;


class Router
{
    protected $controller;


    public function __construct()
    {
        $this->setController();
    }

    public function setController(){
        $routes = yaml_parse_file(ROOT_DIR.'/config/routes.yml');
        foreach ($routes as $route){
            if (preg_match('#'.$route['uri'].'#',$_SERVER['REQUEST_URI'], $matches)){
                $controllerClass = '\Controller\\'.$route['zone'].'Controller\\'.$route['controller'];
                $params = array_combine($route['params'], array_slice($matches,1));
                return $this->controller = new $controllerClass($route['action'],$params);
            }
        }
        $this->controller = new \Controller\PublicController\HomeController('error404', '');
    }

    public function getController()
    {
        return $this->controller;
    }
}
<?php

namespace Core;

class Router
{
    const DEFAULT_MODULE = 'home';
    const NO_ROUTE = 1;
    protected $routes = array();
    public $module, $action, $currentUrl;


    public function __construct()
    {
        $this->setModule();
        $this->setAction();
        $this->currentUrl = $this->getRequestUrl();
    }


    public function setModule()
    {
        if (isset($_GET['module'])) {
            $this->module = $_GET['module'];
        } else {
            $this->module = self::DEFAULT_MODULE;
        }
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setAction()
    {
        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        } else {
            $this->action = '';
        }
    }

    public function getAction()
    {
        return $this->action;
    }

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    public function getRoute()
    {
        foreach ($this->routes as $route) {
            if ($route->match($this->currentUrl) ){
                return $route;
            }
        }

        throw new RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    public function getRequestUrl(){
        $url = $_SERVER['REQUEST_URI'];
        return $url;
    }

    public function getController()
    {
        $route = $this->getRoute();
        $controllerClass = "Controller\\".$route->getModule()."Controller";
        return new $controllerClass($route->getAction(), $route->getParams());
    }


}
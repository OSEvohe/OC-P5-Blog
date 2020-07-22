<?php

namespace Core;

class Router
{
    const DEFAULT_MODULE = 'Home';
    const NO_ROUTE = 1;
    protected $routes = array();
    public $module, $action, $currentUrl;


    public function __construct()
    {
        $this->setModule();
        $this->setAction();
        $this->setCurrentUrl();
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
            if (($routeVars = $route->match($this->currentUrl)) !== false) {
                if ($route->hasParams()) {
                    $listParams = array();
                    $paramsName = $route->getParamsName();
                    foreach ($routeVars as $key => $value) {
                        if ($key !== 0) {
                            $listParams[$paramsName[$key - 1]] = $value;
                        }
                    }
                    $route->setParams($listParams);
                }
                return $route;
            }
        }

        return new Route(null, 'Home', 'error404');
    }

    public function setCurrentUrl()
    {
        $this->currentUrl = $_SERVER['REQUEST_URI'];
    }

    public function getController()
    {
        $route = $this->getRoute();
        $controllerClass = "Controller\\" . $route->getModule() . "Controller";
        return new $controllerClass($route->getAction(), $route->getParams());
    }


}
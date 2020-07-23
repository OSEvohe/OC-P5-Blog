<?php

namespace Core;


class Router
{
    const DEFAULT_MODULE = 'Home';
    const DEFAULT_ZONE = 'Public';
    const NO_ROUTE = 1;
    protected $routes = array();
    protected $zone;
    protected $currentUrl;


    public function __construct()
    {
        $this->setZone();
        $this->setCurrentUrl();
    }

    public function setZone()
    {
        if (isset($_GET['zone'])) {
            $this->zone = $_GET['zone'];
        } else {
            $this->zone = self::DEFAULT_ZONE;
        }

    }

    public function getZone(){
        return $this->zone;
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
        $controllerClass = 'Controller\\'.$this->getZone() . 'Controller\\' . $route->getModule() . 'Controller';
        return new $controllerClass($route->getAction(), $route->getParams());
    }
}
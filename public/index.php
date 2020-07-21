<?php
require_once ('../src/Core/Router.php');
require_once ('../src/Core/Route.php');
require_once ('../src/Core/Controller.php');
require_once ('../src/Controller/HomeController.php');

$router = new Core\Router();

// TODO : Utiliser un fichier de config
$router->addRoute(new Core\Route('/','Home','show',''));

$controller = $router->getController();
$controller->execute();


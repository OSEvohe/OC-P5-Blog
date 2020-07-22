<?php
require_once ('../src/Core/Router.php');
require_once ('../src/Core/Route.php');
require_once ('../src/Core/Controller.php');
require_once ('../src/Controller/HomeController.php');
require_once('../src/Controller/BlogController.php');

$router = new Core\Router();

// TODO : Utiliser un fichier de config
$router->addRoute(new Core\Route('/','Home','show'));
$router->addRoute(new Core\Route('/blog','Blog','show'));
$router->addRoute(new Core\Route('/blog/post-([0-9]+)','Blog','showPost',['id']));

$controller = $router->getController();
$controller->execute();


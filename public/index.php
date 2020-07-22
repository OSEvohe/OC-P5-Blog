<?php
define('ROOT_DIR', __DIR__.'/..');
define('TEMPLATES_DIR', ROOT_DIR.'/templates');

require_once (ROOT_DIR.'/src/vendor/autoload.php');
require_once (ROOT_DIR.'/src/Core/Router.php');
require_once (ROOT_DIR.'/src/Core/Route.php');
require_once (ROOT_DIR.'/src/Core/Controller.php');
require_once (ROOT_DIR.'/src/Controller/HomeController.php');
require_once (ROOT_DIR.'/src/Controller/BlogController.php');

$router = new Core\Router();

// TODO : Utiliser un fichier de config
$router->addRoute(new Core\Route('/','Home','show'));
$router->addRoute(new Core\Route('/blog','Blog','show'));
$router->addRoute(new Core\Route('/blog/post-([0-9]+)','Blog','showPost',['id']));

$controller = $router->getController();
$controller->execute();


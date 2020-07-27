<?php
define('ROOT_DIR', __DIR__.'/..');
define('TEMPLATES_DIR', ROOT_DIR.'/templates');

require_once (ROOT_DIR.'/vendor/autoload.php');


$router = new Core\Router();

// TODO : Utiliser un fichier de config
$router->addRoute(new Core\Route('/?','Home','show'));
$router->addRoute(new Core\Route('/blog/?','Blog','show'));
$router->addRoute(new Core\Route('/admin/blog/?','Blog','show'));
$router->addRoute(new Core\Route('/blog/post-([0-9]+)/?','Blog','showPost',['id']));
$router->addRoute(new Core\Route('/admin/blog/post-([0-9]+)/?','Blog','showPost',['id']));

$controller = $router->getController();
$controller->execute();


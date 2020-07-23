<?php
define('ROOT_DIR', __DIR__.'/..');
define('TEMPLATES_DIR', ROOT_DIR.'/templates');

require_once (ROOT_DIR.'/src/vendor/autoload.php');
require_once (ROOT_DIR.'/src/Core/Router.php');
require_once (ROOT_DIR.'/src/Core/Route.php');
require_once (ROOT_DIR.'/src/Core/Controller.php');
require_once (ROOT_DIR.'/src/Controller/PublicController.php');
require_once (ROOT_DIR.'/src/Controller/AdminController.php');
require_once(ROOT_DIR . '/src/Controller/Public/HomeController.php');
require_once(ROOT_DIR . '/src/Controller/Public/BlogController.php');
require_once(ROOT_DIR . '/src/Controller/Admin/HomeController.php');
require_once(ROOT_DIR . '/src/Controller/Admin/BlogController.php');

$router = new Core\Router();

// TODO : Utiliser un fichier de config
$router->addRoute(new Core\Route('/?','Home','show'));
$router->addRoute(new Core\Route('/blog/?','Blog','show'));
$router->addRoute(new Core\Route('/admin/blog/?','Blog','show'));
$router->addRoute(new Core\Route('/blog/post-([0-9]+)/?','Blog','showPost',['id']));
$router->addRoute(new Core\Route('/admin/blog/post-([0-9]+)/?','Blog','showPost',['id']));

$controller = $router->getController();
$controller->execute();


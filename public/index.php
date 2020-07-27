<?php
define('ROOT_DIR', __DIR__.'/..');
define('TEMPLATES_DIR', ROOT_DIR.'/templates');

require_once (ROOT_DIR.'/vendor/autoload.php');


$router = new Core\Router();
$controller = $router->getController();
$controller->execute();


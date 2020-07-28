<?php
define('ROOT_DIR', __DIR__.'/..');

require_once (ROOT_DIR.'/vendor/autoload.php');


$router = new Core\Router();
$controller = $router->getController();
$controller->execute();


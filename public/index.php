<?php
define('ROOT_DIR', __DIR__ . '/..');

require_once(ROOT_DIR . '/vendor/autoload.php');


try {
    $router = new Core\Router();
    $controller = $router->getController();
    $controller->execute();
} catch (Throwable $e) {
    die('['. get_class($e).']: '.$e);
}


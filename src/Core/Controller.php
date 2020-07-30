<?php


namespace Core;


use Exceptions\BlogException;
use Exceptions\BlogLoaderError;
use Exceptions\BlogTemplateRenderError;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected $action;
    protected $params;
    protected $twig;
    protected $templateVars = array();

    public function __construct($action, $params)
    {
        $this->action = $action;
        $this->params = $params;

        try {
            $loader = new FilesystemLoader(ROOT_DIR . '/templates');
            $loader->addPath(ROOT_DIR . '/templates/public', 'public');
            $loader->addPath(ROOT_DIR . '/templates/admin', 'admin');

            $this->twig = new Environment($loader);
        } catch (LoaderError $e) {
            try {
                throw new BlogLoaderError($e->getMessage());
            } catch (BlogLoaderError $e) {
                die($e);
            }
        }
    }

    public function execute()
    {
        $method = 'execute' . ucfirst($this->action);
        $this->$method();
    }

    protected function render($templateFile)
    {
        try {
            $template = $this->twig->load($templateFile);
        } catch (LoaderError $e) {
            try {
                throw new BlogLoaderError($e->getMessage());
            } catch (BlogLoaderError $e) {
                die($e);
            }
        }

        try {
            echo $template->render($this->templateVars);
        } catch (\Throwable $e) {
            try {
                throw new BlogTemplateRenderError($e->getMessage());
            } catch (BlogTemplateRenderError $e) {
                die($e);
            }
        }
    }
}
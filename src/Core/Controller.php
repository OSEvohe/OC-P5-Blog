<?php


namespace Core;


use Exceptions\BlogException;
use Exceptions\BlogControllerLoaderError;
use Exceptions\BlogTemplateLoadError;
use Exceptions\BlogTemplateRenderError;
use Twig\Environment;
use Twig\Error\Error;
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
            $this->loadTwigConfig();
        } catch (Error $e) {
            throw new BlogControllerLoaderError($e->getMessage());
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
        } catch (Error $e) {
            throw new BlogTemplateLoadError($e->getMessage());
        }

        try {
            echo $template->render($this->templateVars);
        } catch (\Throwable $e) {
            throw new BlogTemplateRenderError($e->getMessage());
        }
    }

    protected function displayError($e)
    {
        $this->templateVars['error'] = $e;
        $this->render('@public/error.html.twig');
    }

    private function loadTwigConfig()
    {
        $loader = new FilesystemLoader(ROOT_DIR . '/templates');
        $loader->addPath(ROOT_DIR . '/templates/public', 'public');
        $loader->addPath(ROOT_DIR . '/templates/admin', 'admin');

        $this->twig = new Environment($loader);

        $config = yaml_parse_file(ROOT_DIR . '/config/config.yml');
        $this->twig->addGlobal('locale', $config['locale']);
        $this->twig->addGlobal('charset', $config['charset']);
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
    }

    protected function isFormSubmit($submitName)
    {
        return (isset($_POST) && isset($_POST[$submitName]));
    }
}
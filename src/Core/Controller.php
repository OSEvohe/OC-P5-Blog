<?php


namespace Core;


use Entity\User;
use Exceptions\BlogControllerLoaderError;
use Exceptions\BlogTemplateLoadError;
use Exceptions\BlogTemplateRenderError;
use Services\BlogAuth;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected $config;
    protected $action;
    protected $params;
    protected $twig;
    protected $templateVars = array();
    protected $user;
    protected $zone;

    public function __construct($action, $params, $zone = 'Public')
    {
        $this->action = $action;
        $this->params = $params;
        $this->zone = $zone;

        $this->user = new BlogAuth();
        if ($this->zone == 'Admin') {
            if (!$this->user->isConnected()) {
                $_SESSION['auth']['return'] = $_SERVER['REQUEST_URI'];
                $this->redirect('/login');
            }
            if (!$this->user->getUser()->hasRole(User::ROLE_ADMIN)) {
                $this->redirect('/');
            }
        }

        if ($this->user->isConnected()) {
            $this->templateVars['userInfo'] = $this->user->getUser();
        }

        try {
            $this->config = yaml_parse_file(ROOT_DIR . '/config/config.yml');
            $this->setTwigConfig();
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

    protected function addErrors(array $errors)
    {
        foreach ($errors as $key => $error) {
            $this->templateVars['errors'][$key] = $error;
        }
    }

    private function setTwigConfig()
    {
        $loader = new FilesystemLoader(ROOT_DIR . '/templates');
        $loader->addPath(ROOT_DIR . '/templates/public', 'public');
        $loader->addPath(ROOT_DIR . '/templates/admin', 'admin');

        $this->twig = new Environment($loader);

        $this->twig->addGlobal('locale', $this->config['locale']);
        $this->twig->addGlobal('charset', $this->config['charset']);
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }

    protected function isFormSubmit($submitName)
    {
        return (isset($_POST) && isset($_POST[$submitName]));
    }
}
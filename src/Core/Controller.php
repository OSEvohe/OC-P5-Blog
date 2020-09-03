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
        $this->restrictAdminZone();
        $this->userInfoToTemplateVars();

        try {
            $this->config = yaml_parse_file(ROOT_DIR . '/config/config.yml');
            $this->setTwigConfig();
        } catch (Error $e) {
            throw new BlogControllerLoaderError($e->getMessage());
        }
    }


    /**
     * Execute the action corresponding to the route
     **/
    public function execute()
    {
        $method = 'execute' . ucfirst($this->action);
        $this->$method();
    }

    /**
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->config[$key];
    }


    /**
     * Render the page
     * @param $templateFile
     * @throws BlogTemplateLoadError
     * @throws BlogTemplateRenderError
     */
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


    /**
     * add errors reported during form processing to the template
     * @param array $errors
     */
    public function addFormErrors(array $errors)
    {
        foreach ($errors as $key => $error) {
            $this->templateVars['errors'][$key] = $error;
        }
    }


    /**
     * Init the Twig configuration
     **/
    private function setTwigConfig()
    {
        $loader = new FilesystemLoader(ROOT_DIR . '/templates');
        $loader->addPath(ROOT_DIR . '/templates/public', 'public');
        $loader->addPath(ROOT_DIR . '/templates/admin', 'admin');

        $this->twig = new Environment($loader);

        $this->twig->addGlobal('locale', $this->config['locale']);
        $this->twig->addGlobal('charset', $this->config['charset']);
    }


    /**
     * Redirect to the $url
     * If $url is 0 (integer) redirect to url stored in $_SESSION['auth']['return']
     * @param mixed $url to redirect to or 0 to redirect to 'return' url
     **/
    public function redirect($url)
    {
        if (isset($_SESSION['auth']['return']) && $url === 0) {
            header('Location: ' . $_SESSION['auth']['return']);
        } elseif ($url !== 0){
            header('Location: ' . $url);
        } else {
            header('Location: /');
        }

        exit();
    }

    public function redirect404(){

        header("Location: /404");
        exit();
    }


    /*
     * Test if a form is submitted using a button named $submitName
     */
    protected function isFormSubmit($submitName)
    {
        return (isset($_POST) && isset($_POST[$submitName]));
    }


    /**
     * If the route is in Admin zone, restrict access to user with Admin role
     **/
    private function restrictAdminZone() : void{
        if ($this->zone == 'Admin') {
            if (!$this->user->isConnected()) {
                $_SESSION['auth']['return'] = $_SERVER['REQUEST_URI'];
                $this->redirect('/login');
            }
            if (!$this->user->getUser()->hasRole(User::ROLE_ADMIN)) {
                $this->redirect('/');
            }
        }
    }


    /**
     * Send info about the connected user to the template vars
     **/
    private function userInfoToTemplateVars() : void{
        if ($this->user->isConnected()) {
            $this->templateVars['userInfo'] = $this->user->getUser();
        }
    }
}
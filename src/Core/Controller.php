<?php


namespace Core;


class Controller
{
    protected $action;
    protected $params;
    protected $twig;
    protected $template;
    protected $templateVars;

    public function __construct($action, $params)
    {
        $this->setAction($action);
        $this->setParams($params);

        $loader = new \Twig\Loader\FilesystemLoader(TEMPLATES_DIR);
        $this->twig = new \Twig\Environment($loader);
    }

    public function execute()
    {
        $method = 'execute' . ucfirst($this->getAction());

        $this->$method($this->getParams());
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

}
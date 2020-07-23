<?php


namespace Core;


use Twig\TemplateWrapper;

abstract  class Controller
{
    protected $action;
    protected $params;
    protected $page;

    public function __construct($action, $params)
    {
        $this->setAction($action);
        $this->setParams($params);
        $this->page = new Page();
    }

    abstract function addContent($contentName, $templateFile, $templateVars);
    abstract function loadLayout();

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
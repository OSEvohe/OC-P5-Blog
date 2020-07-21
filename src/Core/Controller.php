<?php


namespace Core;


class Controller
{
    protected $action;
    protected $params;

    public function __construct($action, $params)
    {
        $this->setAction($action);
        $this->setParams($params);
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
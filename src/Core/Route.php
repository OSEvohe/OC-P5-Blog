<?php


namespace Core;


class Route
{
    protected $module;
    protected $action;
    protected $url;
    protected $params = array();


    public function __construct($url, $module, $action, $params)
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setParams($params);
    }

    public function getModule()
    {
        return $this->module;
    }


    public function setModule($module)
    {
        $this->module = $module;
    }


    public function getAction()
    {
        return $this->action;
    }


    public function setAction($action)
    {
        $this->action = $action;
    }


    public function getUrl()
    {
        return $this->url;
    }


    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function getParams()
    {
        return $this->params;
    }


    public function setParams($params)
    {
        $this->params = $params;
    }

    public function match($url)
    {
        if (preg_match('`^'.$this->getUrl().'$`', $url, $matches))
        {
            return $matches;
        }
        else
        {
            return false;
        }
    }

}
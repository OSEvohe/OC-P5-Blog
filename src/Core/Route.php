<?php


namespace Core;


class Route
{
    protected $module;
    protected $action;
    protected $url;
    protected $paramsName;
    protected $params = array();


    public function __construct($url, $module, $action, array $paramsName = [])
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setParamsName($paramsName);
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


    public function getParamsName()
    {
        return $this->paramsName;
    }


    public function setParamsName($paramsName)
    {
        $this->paramsName = $paramsName;
    }

    public function setParams(array $params){
        $this->params = $params;
    }

    public function getParams(){
        return $this->params;
    }

    public function hasParams(){
        return !empty($this->getParamsName());
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
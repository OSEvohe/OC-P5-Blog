<?php


namespace Controller;


use Core\Controller;

abstract class PublicController extends Controller{

    public function __construct($action, $params)
    {
        parent::__construct($action, $params);
        $this->loadLayout();
    }

    public function loadLayout(){
        $this->page->loadTemplate('public/layout.html');
    }

    public function addContent($contentName, $templateFile, $templateVars = array()){
        $contentTemplate  = $this->page->twig->load('public/'.$templateFile);
        $this->page->addContent($contentName,$contentTemplate, $templateVars);
    }
}
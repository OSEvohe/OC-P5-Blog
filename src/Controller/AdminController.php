<?php


namespace Controller;


use Core\Controller;

class AdminController extends Controller
{
    public function loadLayout(){
        $this->page->setLayout('admin/layout.html');
    }

    public function addContent($contentName, $templateFile, $templateVars){
        $this->page->addContent('admin/'.$templateFile);
    }
}
<?php


namespace Core;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Page
{
    public $twig;
    public $title;
    public $template;
    public $templateVars = array();
    public $pageVars = array();

    public function __construct()
    {
        $loader = new FilesystemLoader(TEMPLATES_DIR);
        $this->twig = new Environment($loader);
    }

    public function display()
    {
        if (!empty($this->templateVars)){
            foreach ($this->templateVars as $contentName => $content){
                $this->pageVars[$contentName] = $content['contentTemplate']->render($content['contentVars']);
            }
        }
        $this->template->display($this->pageVars);
    }

    public function loadTemplate($templateFile)
    {
        $this->template = $this->twig->load($templateFile);
    }

    public function addVar($varName, $value)
    {
        $this->pageVars[$varName] = $value;
    }

    public function addContent($contentName, $template, $templateVars = array()){
        $this->templateVars[$contentName] = ["contentTemplate" => $template, "contentVars" => $templateVars];
    }
}
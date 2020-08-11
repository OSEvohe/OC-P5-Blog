<?php


namespace Controller\PublicController;

use Core\Controller;
use Models\ProfileManager;


class HomeController extends Controller
{
    public function executeShow()
    {
        $profile = (new ProfileManager())->findAll('profile');
        if (!empty($profile))
            $this->templateVars['profile'] = $profile[0];

        $this->render('@public/index.html.twig');
    }

    public function executeError404()
    {
        $this->render('@public/error404.html.twig');
    }
}
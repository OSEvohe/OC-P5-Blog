<?php


namespace Controller\PublicController;

use Core\Controller;
use Models\ProfileManager;
use Services\SocialGenerator;


class HomeController extends Controller
{
    use SocialGenerator;

    public function executeShow()
    {
        $profile = (new ProfileManager())->findAll('profile');
        if (!empty($profile)) {
            $this->templateVars['profile'] = $profile[0];
        }
        $this->getSocialNetworks();
        $this->render('@public/index.html.twig');
    }

    public function executeError404()
    {
        $this->getSocialNetworks();
        $this->render('@public/error404.html.twig');
    }
}
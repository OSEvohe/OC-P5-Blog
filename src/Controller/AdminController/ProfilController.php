<?php


namespace Controller\AdminController;

use Core\Controller;
use Models\ProfileManager;
use Models\SocialNetworkManager;


class ProfilController extends Controller
{
    public function executeShow()
    {
        $this->templateVars['profile'] = (new ProfileManager())->findAll('profile')[0];
        $this->render('@admin/profil.html.twig');
    }

    public function executeShowSocial()
    {
        $this->templateVars['socialNetworks'] = (new SocialNetworkManager())->findAll('socialNetwork', ['name' => 'ASC']);
        $this->render('@admin/social.html.twig');
    }

    public function executeEditSocial()
    {
        $this->templateVars['network'] = (new SocialNetworkManager())->findOneBy('socialNetwork', ['id' => $this->params['id']]);
        $this->render('@admin/social_edit.html.twig');
    }

    public function executeDeleteSocial()
    {
        $this->templateVars['network'] = (new SocialNetworkManager())->findOneBy('socialNetwork', ['id' => $this->params['id']]);
        $this->render('@admin/social_delete.html.twig');
    }
}
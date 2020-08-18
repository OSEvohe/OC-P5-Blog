<?php


namespace Controller\AdminController;

use Core\Controller;
use Core\DataValidator;
use Entity\SocialNetwork;
use Models\ProfileManager;
use Models\SocialNetworkManager;


class ProfilController extends Controller
{
    public function executeShow()
    {
        $profile = (new ProfileManager())->findAll()[0];

        if ($this->isFormSubmit('profile_nameSubmit') || $this->isFormSubmit('profile_teasingSubmit')) {
            $this->hydrateEntityFromPOST($profile);

            (new ProfileManager())->update($profile);
            $this->redirect('/admin/profile');
        }

        $this->templateVars['profile'] = $profile;
        $this->render('@admin/profil.html.twig');
    }

    public function executeShowSocial()
    {
        if ($this->isFormSubmit('social_newSubmit')){
            $network = new SocialNetwork();
            $this->hydrateEntityFromPOST($network);

            (new SocialNetworkManager())->create($network);
            $this->redirect('/admin/social');
        }

        $this->templateVars['socialNetworks'] = (new SocialNetworkManager())->findAll(['name' => 'ASC']);
        $this->render('@admin/social.html.twig');
    }

    public function executeEditSocial()
    {
        $network = (new SocialNetworkManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('social_editSubmit')){
            $this->hydrateEntityFromPOST($network);

            (new SocialNetworkManager())->update($network);
            $this->redirect('/admin/social');
        }
        $this->templateVars['network'] = $network;
        $this->render('@admin/social_edit.html.twig');
    }

    public function executeDeleteSocial()
    {
        $network = (new SocialNetworkManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('social_deleteCancel')) {
            $this->redirect('/admin/social');
        }

        if ($this->isFormSubmit('social_deleteSubmit')){
            (new SocialNetworkManager())->delete($network);
            $this->redirect('/admin/social');
        }

        $this->templateVars['network'] = $network;
        $this->render('@admin/social_delete.html.twig');
    }
}
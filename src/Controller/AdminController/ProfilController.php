<?php


namespace Controller\AdminController;

use Core\Controller;
use Entity\Profile;
use Entity\SocialNetwork;
use Models\ProfileManager;
use Models\SocialNetworkManager;
use Services\FileUploader;


class ProfilController extends Controller
{
    public function executeShow()
    {
        $profile = (new ProfileManager())->findAll()[0];

        $this->processPhotoForm($profile);
        $this->processCVForm($profile);

        if ($this->isFormSubmit('profile_nameSubmit') || $this->isFormSubmit('profile_teasingSubmit')) {
            $profile->hydrate($_POST);

            if ($profile->isValid()) {
                (new ProfileManager())->update($profile);
                $this->redirect('/admin/profile');
            }
        }

        $this->addFormErrors($profile->getConstraintsErrors());
        $this->templateVars['profile'] = $profile;
        $this->render('@admin/profil.html.twig');
    }

    public function executeShowSocial()
    {
        if ($this->isFormSubmit('social_newSubmit')) {
            $network = new SocialNetwork();
            $network->hydrate($_POST);

            if ($network->isValid()) {
                (new SocialNetworkManager())->create($network);
                $this->redirect('/admin/social');
            }
            $this->templateVars['errors'] = $network->getConstraintsErrors();
        }
        $this->templateVars['socialNetworks'] = (new SocialNetworkManager())->findAll(['name' => 'ASC']);
        $this->render('@admin/social.html.twig');
    }

    public function executeEditSocial()
    {
        $network = (new SocialNetworkManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('social_editSubmit')) {
            $network->hydrate($_POST);

            if ($network->isValid()) {
                (new SocialNetworkManager())->update($network);
                $this->redirect('/admin/social');
            }
        }

        $this->templateVars['errors'] = $network->getConstraintsErrors();
        $this->templateVars['network'] = $network;
        $this->render('@admin/social_edit.html.twig');
    }

    public function executeDeleteSocial()
    {
        $network = (new SocialNetworkManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('social_deleteCancel')) {
            $this->redirect('/admin/social');
        }

        if ($this->isFormSubmit('social_deleteSubmit')) {
            (new SocialNetworkManager())->delete($network);
            $this->redirect('/admin/social');
        }

        $this->templateVars['network'] = $network;
        $this->render('@admin/social_delete.html.twig');
    }


    /**
     * Upload the Logo/Photo on the server then save its url in database
     * @param Profile $profile An already hydrated Profile
     */
    private function processPhotoForm(Profile $profile)
    {
        if ($this->isFormSubmit('profile_photoSubmit')) {
            $uploader = new FileUploader('/uploads', 'profilePhoto');
            $uploader->setMaxSize(256000);
            if ($uploader->upload()) {
                $profile->setPhotoUrl($uploader->getFileUrl());

                if ($profile->isValid()) {
                    (new ProfileManager())->update($profile);
                    $this->redirect('/admin/profile');
                }
            } else {
                $this->addFormErrors(['photoUpload' => $uploader->getErrors()]);
            }
        }
    }


    /**
     * Upload the PDF on the server then save its url in database
     * @param Profile $profile An already hydrated Profile
     */
    private function processCVForm(Profile $profile)
    {
        if ($this->isFormSubmit('profile_CvSubmit')) {
            $uploader = new FileUploader('/uploads', 'profileCv');
            $uploader->setMaxSize(1048576);
            $uploader->setMimeTypeAllowed(FileUploader::MIME_TYPE_PDF);
            if ($uploader->upload()) {
                $profile->setCvUrl($uploader->getFileUrl());

                if ($profile->isValid()) {
                    (new ProfileManager())->update($profile);
                    $this->redirect('/admin/profile');
                }
            } else {
                $this->addFormErrors(['CvUpload' => $uploader->getErrors()]);
            }
        }
    }
}
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

    const FORM_CV_INPUT = 'Cv';
    const FORM_PHOTO_INPUT = 'Photo';


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
     * Process the Photo/Logo upload form then save its url in database
     * @param Profile $profile An already hydrated Profile
     */
    private function processPhotoForm(Profile $profile): void
    {
        if ($this->isFormSubmit('profile_photoSubmit')) {
            if ($this->uploadProfileFile($profile, self::FORM_PHOTO_INPUT, FileUploader::MIME_TYPE_IMAGE, 384000)) {
                if ($profile->isValid()) {
                    (new ProfileManager())->update($profile);
                    $this->redirect('/admin/profile');
                }
            }
        }
    }


    /**
     * Process the CV upload form then save its url in database
     * @param Profile $profile An already hydrated Profile
     */
    private function processCVForm(Profile $profile): void
    {
        if ($this->isFormSubmit('profile_cvSubmit')) {
            if ($this->uploadProfileFile($profile, self::FORM_CV_INPUT, FileUploader::MIME_TYPE_PDF, 10248576)) {
                if ($profile->isValid()) {
                    (new ProfileManager())->update($profile);
                    $this->redirect('/admin/profile');
                }
            }
        }
    }


    /**
     * Upload the CV or Photo File
     * @param Profile $profile
     * @param string $isCvOrPdf
     * @param string $inputName
     * @param array $mimeType
     * @param int $maxSize
     * @return bool
     */
    private function uploadProfileFile(Profile $profile, string $isCvOrPdf, array $mimeType, int $maxSize): bool
    {
        if (in_array($isCvOrPdf, [self::FORM_CV_INPUT, self::FORM_PHOTO_INPUT])) {
            $uploader = new FileUploader('/uploads', $isCvOrPdf, $mimeType, $maxSize);

            if ($uploader->upload()) {
                $method = 'set'.$isCvOrPdf.'Url';
                $profile->$method($uploader->getFileUrl());
                return true;
            }
            $this->addFormErrors(['ProfileUpload' => $uploader->getErrors()]);
        }
        return false;
    }
}
<?php


namespace Controller\PublicController;

use Core\Controller;
use Models\ProfileManager;
use Services\EmailSender;
use Services\SocialGenerator;


class HomeController extends Controller
{
    use SocialGenerator;

    public function executeShow()
    {
        if ($this->processContactForm()) {
            $_SESSION['contact-sent'] = true;
            $this->redirect('/confirmation-contact');
        }

        $profile = (new ProfileManager())->findAll();
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


    public function executeConfirmContact()
    {
        if (!empty($_SESSION['contact-sent'])) {
            unset($_SESSION['contact-sent']);
            $this->getSocialNetworks();
            $this->render('@public/contact-confirm.html.twig');
        } else {
            $this->redirect('/');
        }
    }


    private function processContactForm()
    {
        if ($this->isFormSubmit('contactSubmit')) {
            $mailer = new EmailSender($this, $_POST);
            $this->renderMailContact($mailer);
            return $mailer->send();
        }
        return false;
    }


    /**
     * @param EmailSender $mailer
     */
    private function renderMailContact(EmailSender $mailer): void
    {
        $vars = ['senderName' => $mailer->getSenderName(),
            'fromAddress' => $mailer->getFromAddress(),
            'content' => $mailer->getBody()
        ];
        $mailer->setBodyHTML($this->twig->render('@public/contactMail.html.twig', $vars));
    }
}
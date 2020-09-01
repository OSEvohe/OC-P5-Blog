<?php


namespace Services;


use Core\Controller;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    private $controller;
    private $mailManager;
    private $toAddress;
    private $fromAddress;
    private $senderName;
    private $body;
    private $bodyHTML;


    public function __construct(Controller $controller, array $data = [])
    {
        $this->controller = $controller;
        $this->configurePHPMailer();
        $this->toAddress = $this->controller->getConfig('adminEmail');
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    private function hydrate(array $data)
    {
        $this->fromAddress = $data['fromAddress'];
        $this->senderName = $data['senderName'];
        $this->body = $data['body'];
    }

    private function validateFields()
    {
        $error = [];
        if (!filter_var($this->fromAddress, FILTER_VALIDATE_EMAIL)) {
            $error[] = "Adresse E-mail invalide";
        }
        if (!filter_var($this->senderName, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[\pL\pM\p{Zs}.-]{2,40}$/u']])) {
            $error[] = "Nom invalide";
        }
        if (strlen($this->body) < 10) {
            $error[] = "Message trop court (minimum 10 caractères)";
        }

        $this->controller->addFormErrors(['emailForm' => $error]);
        return (empty($error));
    }

    public function send()
    {
        if ($this->validateFields()) {
            $this->mailManager->addAddress($this->toAddress);
            $this->mailManager->Body = $this->bodyHTML;

            return $this->mailManager->send();
        } else {
            return false;
        }
    }

    private function configurePHPMailer()
    {
        $config = $this->controller->getConfig('phpmailer');
        $this->mailManager = new PHPMailer(TRUE);
        $this->mailManager->isHTML();
        $this->mailManager->Encoding = PHPMailer::ENCODING_BASE64;
        $this->mailManager->CharSet = PHPMailer::CHARSET_UTF8;

        $this->mailManager->setFrom($config['smtpMailFromAddress'], 'Blog P5');
        $this->mailManager->Subject = "Nouveau message depuis vote Blog";

        $this->configureSMTP($config);

    }


    /**
     * @param mixed $fromAddress
     */
    public function setFromAddress($fromAddress): void
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @param mixed $senderName
     */
    public function setSenderName($senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $config
     */
    private function configureSMTP($config): void
    {
        $this->mailManager->isSMTP();
        $this->mailManager->Host = $config['smtpServer'];
        $this->mailManager->SMTPSecure = $config['smtpSecurity'];
        $this->mailManager->Port = $config['smtpServerPort'];
        if ($config['smtpUseAuth']) {
            $this->mailManager->SMTPAuth = TRUE;
            $this->mailManager->Username = $config['smtpAuthLogin'];
            $this->mailManager->Password = $config['smtpAuthPassword'];
        }
    }

    /**
     * @param $bodyHTML
     */
    public function setBodyHTML($bodyHTML):void
    {
        $this->bodyHTML = $bodyHTML;
    }


}
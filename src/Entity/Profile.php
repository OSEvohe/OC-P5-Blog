<?php


namespace Entity;

use Core\ConstraintableEntity;
use Core\Entity;

class Profile extends Entity
{
    use ConstraintableEntity;

    /** @var string */
    protected $lastName;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $photoUrl;
    /** @var string */
    protected $cvUrl;
    /** @var string */
    protected $teasing;

    /**
     * Profile constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->addConstraints([
            'lastName' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[\pL\pM\p{Zs}.-]{2,40}$/u'],
                'msg' => 'Nom invalide, 2 à 40 caractères alphanumériques autorisés']],

            'firstName' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[\pL\pM\p{Zs}.-]{2,20}$/u'],
                'msg' => 'Prénom invalide, 2 à 20 caractères alphanumériques autorisés']],

            'photoUrl' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^.*\/uploads\/[\w\d-]{1,50}\.(gif|jpeg|png|jpg)$/'],
                'nullable' => true, 'msg' => 'Fichier de la photo invalide ou nom trop long'
            ], [
                'filter' => FILTER_VALIDATE_URL, 'nullable' => true, 'msg' => 'URL du fichier de la photo/logo invalide']],

            'cvUrl' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^.*\/uploads\/[\w\d-]{1,50}\.pdf$/'],
                'nullable' => true, 'msg' => 'Fichier du CV invalide ou nom trop long'
            ], [
                'filter' => FILTER_VALIDATE_URL, 'nullable' => true, 'msg' => 'URL du fichier du CV invalide']],

            'teasing' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^.{4,130}$/u'],
                'msg' => 'Phrase d\'accroche invalide, 4 à 130 caractères autorisés']]]);
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getPhotoUrl(): string
    {
        if (is_null($this->photoUrl)) {
            return '';
        }
        return $this->photoUrl;
    }

    /**
     * @param string $photoUrl
     */
    public function setPhotoUrl(string $photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return string
     */
    public function getCvUrl(): string
    {
        if (is_null($this->cvUrl)) {
            return '';
        }
        return $this->cvUrl;
    }

    /**
     * @param string $cvUrl
     */
    public function setCvUrl(string $cvUrl): void
    {
        $this->cvUrl = $cvUrl;
    }

    /**
     * @return string
     */
    public function getTeasing(): string
    {
        return $this->teasing;
    }

    /**
     * @param string $teasing
     */
    public function setTeasing(string $teasing): void
    {
        $this->teasing = $teasing;
    }
}
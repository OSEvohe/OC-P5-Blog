<?php


namespace Entity;

use Core\ConstraintableEntity;
use Core\Entity;

class SocialNetwork extends Entity
{
    use ConstraintableEntity;

    /** @var string */
    protected $name;
    /** @var string */
    protected $iconClass;
    /** @var string */
    protected $profileUrl;


    /**
     * Profile constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->addConstraints([
            'name' => [
                [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^.{2,30}$/u'],
                    'msg' => 'Nom du réseau social invalide, 2 à 30 caractéres alphanumériques autorisés'
                ]],
            'iconClass' => [
                [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^fa[sb]? fa-[a-z-]{1,23}$/u'],
                    'msg' => 'Format de la classe font-awesome invalide'
                ]],
            'profileUrl' => [
                [
                    'filter' => FILTER_VALIDATE_URL,
                    'msg' => 'Url du profil invalide'
                ]
            ]
        ]);
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIconClass(): string
    {
        return $this->iconClass;
    }

    /**
     * @param string $iconClass
     */
    public function setIconClass(string $iconClass): void
    {
        $this->iconClass = $iconClass;
    }

    /**
     * @return string
     */
    public function getProfileUrl(): string
    {
        return $this->profileUrl;
    }

    /**
     * @param string $profileUrl
     */
    public function setProfileUrl(string $profileUrl): void
    {
        $this->profileUrl = $profileUrl;
    }
}
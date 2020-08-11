<?php


namespace Entity;

use Core\Entity;

class Profile extends Entity
{
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
        if (is_null($this->photoUrl))
            return '';
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
        if (is_null($this->cvUrl))
            return '';
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
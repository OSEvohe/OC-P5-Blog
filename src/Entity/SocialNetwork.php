<?php


namespace Entity;

use Core\Entity;

class SocialNetwork extends Entity
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $iconClass;
    /** @var string */
    protected $profileUrl;

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
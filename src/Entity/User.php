<?php


namespace Entity;

use Core\Entity;
use Core\TimestampableEntity;

class User extends Entity
{
    use TimestampableEntity;

    /** @var string */
    private $login;
    /** @var string */
    private $passwordHash;
    /** @var string */
    private $displayName;
    /** @var string */
    private $role;

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return array
     */
    public function getRole(): array
    {
        return unserialize($this->role);
    }

    /**
     * @param array $role
     */
    public function setRole(array $role): void
    {
        $this->role = serialize($role);
    }
}
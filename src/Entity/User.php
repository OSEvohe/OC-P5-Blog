<?php


namespace Entity;

use Core\Entity;

class User extends Entity
{
    /** @var string */
    protected $login;
    /** @var string */
    protected $passwordHash;
    /** @var string */
    protected $displayName;
    /** @var string */
    protected $role;

    const ROLE_GUEST = 'guest';
    const ROLE_MEMBER = 'member';
    const ROLE_ADMIN = 'admin';

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
        $role = unserialize($this->role);
        if (empty($role))
            $role[] = self::ROLE_GUEST;
        return $role;
    }

    /**
     * @param array $role
     */
    public function setRole(array $role): void
    {
        $this->role = serialize($role);
    }
}
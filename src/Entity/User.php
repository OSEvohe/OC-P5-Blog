<?php


namespace Entity;

use Core\ConstraintableEntity;
use Core\Entity;

class User extends Entity
{
    use ConstraintableEntity;

    /** @var string */
    protected $login;
    /** @var string */
    protected $passwordHash;
    /** @var string */
    protected $displayName;
    /** @var string */
    protected $role;
    /** @var string */
    protected $token;
    /** @var int */
    protected $tokenTime;
    /** @var bool */
    protected $isValid;

    const ROLE_MEMBER = 'member';
    const ROLE_ADMIN = 'admin';

    protected static $constraints = [];


    /**
     * Profile constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->addConstraints([
            'login' => [
                [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^\[a-zA-Z0-9_]{4,50}$/u'],
                    'msg' => 'Nom d\'utilisateur invalide, 4 à 50 caractères non spéciaux autorisés'
                ]],
            'passwordHash' => [
                [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^.{60}$/u'],
                    'msg' => 'Erreur dans la hash du mot de passe'
                ]],
            'displayName' => [
                [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^\w{4,20}$/u'],
                    'msg' => 'Pseudo invalide 4 à 20 caractères alphanumérique autorisés'
                ]
            ]
        ]);
    }


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
            $role[] = self::ROLE_MEMBER;
        return $role;
    }

    /**
     * @param array $role
     */
    public function setRole(array $role): void
    {
        $this->role = serialize($role);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getTokenTime(): int
    {
        return $this->tokenTime;
    }

    public function setTokenTime(int $tokenTime): void
    {
        $this->tokenTime = $tokenTime;
    }

    public function getIsValid(): bool
    {
        return $this->isValid();
    }

    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }

    public function hasRole(string $role): bool
    {
        return array_search($role, $this->getRole())!== false;
    }

}

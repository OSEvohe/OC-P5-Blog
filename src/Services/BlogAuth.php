<?php


namespace Services;

use Core\Controller;
use Entity\User;
use Models\UserManager;

session_start();

class BlogAuth
{
    private $controller;
    private $secret_key;
    private $token;
    /* @var User */
    private $user;
    /* @var bool */
    private $isConnected = false;

    const TOKEN_EXPIRE_TIME = 1800;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->secret_key = yaml_parse_file(ROOT_DIR . '/config/auth-config.yml')['secret_key'];
        $this->user = new User();
        if ($this->isUserConnected()){
            $this->isConnected = true;
        }
    }


    public function connectUser($login, $password)
    {
        $this->user = ((new UserManager())->findOneBy(['login' => $login]));
        if ($this->isPasswordValid($password)) {
            $this->createToken($this->user->getId(), $this->userData());
            $this->saveToken();
            $this->sendToken();
            return true;
        }
        return false;
    }

    private function isUserConnected(): bool
    {
        if ($this->sessionExists()) {
            $this->user = ((new UserManager())->findOneBy(['id' => $_SESSION['auth']['userId']]));
            $this->token['token'] = $_SESSION['auth']['token'];
            if ($this->user) {
                return $this->verifyToken($this->user->getId(), $this->userData(), $this->user->getTokenTime());
            }
        }

        return false;
    }

    private function createToken(int $userId, array $data): void
    {
        $this->token['time'] = time();
        $this->token['token'] = hash('sha256', $this->secret_key . (string)$userId . implode(',', $data) . $this->token['time']);
    }

    private function verifyToken(int $userId, array $data, $time)
    {
        if (isset($this->token['token'])) {
            if ($this->token['token'] == hash('sha256', $this->secret_key . (string)$userId . implode(',', $data) . $time)) {
                if (($time + self::TOKEN_EXPIRE_TIME) < time()) {
                    return false;
                } elseif (($time + self::TOKEN_EXPIRE_TIME / 2) < time()) {
                    $this->renewToken($userId, $data);
                }
                return true;
            }
        }
        return false;
    }

    private function renewToken(int $userId, array $data): void
    {
        $this->createToken($userId, $data);
        $this->saveToken();
        $this->sendToken();
    }

    private function saveToken()
    {
        if ($this->user) {
            $this->user->setToken($this->token['token']);
            $this->user->setTokenTime($this->token['time']);
            (new UserManager())->update($this->user);
        }
    }

    private function sendToken(): void
    {
        $_SESSION['auth']['token'] = $this->user->getToken();
        $_SESSION['auth']['userId'] = $this->user->getId();
    }


    private function sessionExists(): bool
    {
        return isset($_SESSION['auth']) && isset($_SESSION['auth']['token']) && isset($_SESSION['auth']['userId']) && is_int($_SESSION['auth']['userId']);
    }


    private function userData()
    {
        return ['login' => $this->user->getLogin(), 'displayName' => $this->user->getDisplayName()];
    }

    public function disconnectUser()
    {
        unset($_SESSION['auth']);
        $this->controller->redirect('/');
    }


    public function isPasswordValid($password)
    {

        if ($this->user) {
            return password_verify($password, $this->user->getPasswordHash());
        }
        return false;
    }

    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }
}
<?php


namespace Entity;

use Core\ConstraintableEntity;
use Core\Entity;

class Comment extends Entity
{
    use ConstraintableEntity;

    /** @var string */
    protected $content;
    /** @var bool */
    protected $visible;
    /** @var int */
    protected $userId;
    /** @var int */
    protected $postId;

    /**
     * Profile constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->addConstraints([
            'content' => [
                [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/^.{0,255}$/um'],
                    'msg' => 'Texte du contenu invalide'
                ]],
            'visible' => [
                [
                    'filter' => FILTER_VALIDATE_BOOLEAN,
                    'msg' => 'Visibilité invalide'
                ]],
            'postId' => [
                [
                    'filter' => FILTER_VALIDATE_INT,
                    'msg' => 'Identifiant du post invalide'
                ]],
            'userId' => [
                [
                    'filter' => FILTER_VALIDATE_INT,
                    'msg' => 'Identifiant de l\'utilisateur invalide'
                ]]
        ]);
    }


    /** @return string */
    public function getContent(): string
    {
        return $this->content;
    }

    /** @return bool */
    public function getVisible(): bool
    {
        return $this->visible;
    }

    /** @return int */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /** @return int */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }
}
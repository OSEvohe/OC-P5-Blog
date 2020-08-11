<?php


namespace Entity;

use Core\Entity;

class Comment extends Entity
{
    /** @var string */
    protected $content;
    /** @var bool */
    protected $visible;
    /** @var int */
    protected $userId;
    /** @var int */
    protected $postId;


    /** @return string */
    public function getContent(): string
    {
        return $this->content;
    }

    /** @return bool */
    public function isVisible(): bool
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
     * @param bool $visible
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
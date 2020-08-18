<?php


namespace Entity;

use Core\Entity;
use Core\DataValidator;

class Post extends Entity
{
    /** @var string */
    protected $content;
    /** @var string */
    protected $title;
    /** @var string */
    protected $lead;
    /** @var int */
    protected $userId;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        DataValidator::isLengthValid($content, 10, 0, 'content');
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        DataValidator::isLengthValid($title, 5, 60, 'title');

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLead(): string
    {
        return $this->lead;
    }

    /**
     * @param string $lead
     */
    public function setLead(string $lead): void
    {
        DataValidator::isLengthValid($lead, 5, 200, 'lead');
        $this->lead = $lead;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
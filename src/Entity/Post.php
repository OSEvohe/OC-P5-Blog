<?php


namespace Entity;

use Core\Entity;
use Core\FormError;

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
        (strlen($content) < 10) ? FormError::addError('content', FormError::TOO_SHORT) : false;
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
        (strlen($title) < 5) ? FormError::addError('title', FormError::TOO_SHORT) : false;
        (strlen($title) > 60) ? FormError::addError('title', FormError::TOO_LONG) : false;
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
        (strlen($lead) < 5) ? FormError::addError('lead', FormError::TOO_SHORT) : false;
        (strlen($lead) > 200) ? FormError::addError('lead', FormError::TOO_LONG) : false;
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
<?php


namespace Core;


use DateTime;

trait TimestampableEntity
{
    /** @var dateTime */
    private $dateCreated;
    /** @var dateTime */
    private $dateModified;

    /**@return DateTime */
    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     * @return void
     */
    public function setDateCreated(DateTime $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return DateTime
     */
    public function getDateModified(): DateTime
    {
        return $this->dateModified;
    }

    /**
     * @param DateTime $dateModified
     * @return void
     */
    public function setDateModified(DateTime $dateModified): void
    {
        $this->dateModified = $dateModified;
    }




}
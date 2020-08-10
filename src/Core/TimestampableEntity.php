<?php


namespace Core;


use DateTime;
use Exception;

trait TimestampableEntity
{
    /** @var dateTime */
    private $dateCreated;
    /** @var dateTime */
    private $dateModified;

    /**@return DateTime
     * @throws Exception
     */
    public function getDateCreated(): DateTime
    {
        return new DateTime($this->dateCreated);
    }

    /**
     * @param DateTime $dateCreated
     * @return void
     */
    public function setDateCreated(DateTime $dateCreated): void
    {
        $this->dateCreated = $dateCreated->format('Y-m-d H:i:s');
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
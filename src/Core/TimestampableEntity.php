<?php


namespace Core;


use DateTime;
use Exception;

trait TimestampableEntity
{
    /** @var dateTime */
    protected $dateCreated;
    /** @var dateTime */
    protected $dateModified;

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
     * @throws Exception
     */
    public function getDateModified(): DateTime
    {
        if (is_null($this->dateModified)) {
            return $this->getDateCreated();
        }
        return new dateTime($this->dateModified);
    }

    /**
     * @param DateTime $dateModified
     * @return void
     */
    public function setDateModified(DateTime $dateModified): void
    {
        $this->dateModified = $dateModified->format('Y-m-d H:i:s');
    }
}
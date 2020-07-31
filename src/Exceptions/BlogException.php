<?php
namespace Exceptions;


class BlogException extends \Exception
{
    public function __toString()
    {
        return 'ERROR: '.$this->message;
    }
}
<?php


namespace Core;



class FormError
{

    const TOO_SHORT = "trop court";
    const TOO_LONG = "trop long";

    static public $errors = [];

    static public function hasError()
    {
        return !empty(self::$errors);
    }

    static public function addError($fieldLabel, $msg)
    {
        self::$errors[$fieldLabel]['msg'] = $msg;
    }

    static public function getErrors()
    {
        return self::$errors;
    }
}
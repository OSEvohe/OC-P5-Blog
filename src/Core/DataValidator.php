<?php


namespace Core;



class DataValidator
{

    const TOO_SHORT = 'trop court';
    const TOO_LONG = 'trop long';

    static public $errors = [];

    static public function hasError()
    {
        return !empty(self::$errors);
    }

    static public function addError($label, $msg)
    {
        self::$errors[$label]['msg'] = $msg;
    }

    static public function getErrors()
    {
        return self::$errors;
    }

    static public function isLengthValid(string $var, $min, $max = 0 , $label = ''){
        $len = strlen($var);
        if ($len < $min) {
            self::addError($label,self::TOO_SHORT);
            return false;
        }

        if ($len > $max && $max != 0) {
            self::addError($label,self::TOO_LONG);
            return false;
        }

        return true;
    }
}
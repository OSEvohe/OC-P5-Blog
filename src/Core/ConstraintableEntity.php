<?php


namespace Core;


trait ConstraintableEntity
{
    protected static $constraints = [];
    protected static $constraintsErrors = [];


    /**
     * @return bool
     */
    public function isValid()
    {
        foreach (array_keys(self::$constraints) as $key) {
            $constraintsError = $this->checkConstraints($key);
            if (!empty($constraintsError)) {
                static::$constraintsErrors[$key] = $constraintsError;
            }
        }
        if (empty(static::$constraintsErrors)) {
            return true;
        }
        return false;
    }


    /**
     * Add constraints to the Entity
     * @param $varName string
     * @param array $constraints : an array of constraints array<br />Constraint array format : <br />['filter' => filter, <br />'options' => options ,<br />'msg' => string]
     * <br />See <a href="https://www.php.net/manual/en/function.filter-var.php">filter_var documentation</a> for filter and options :
     */
    protected function addConstraints(array $constraints)
    {
        self::$constraints = array_merge(self::$constraints, $constraints);
    }


    /**
     * @param $varName
     * @return false|mixed
     */
    public function getConstraints($varName)
    {
        if (isset(self::$constraints[$varName])) {
            return self::$constraints[$varName];
        }
        return false;
    }


    /**
     * @param $varName
     * @return array;
     */
    public function checkConstraints($varName)
    {
        $method = 'get' . ucfirst($varName);
        $constraints = $this->getConstraints($varName);
        $constraintsErrors = [];

        foreach ($constraints as $constraint)
            if (is_callable([$this, $method])) {
                if ((!filter_var($this->$method(), $constraint['filter'], $constraint) && $this->$method() != null) || (empty($constraint['nullable']) && $this->$method() === '')) {
                    $constraintsErrors[] = $constraint['msg'];
                }
            }

        return $constraintsErrors;
    }


    /**
     * @return array
     */
    public function getConstraintsErrors()
    {
        return self::$constraintsErrors;
    }
}
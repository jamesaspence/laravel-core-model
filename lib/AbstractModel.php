<?php

namespace CoreModel;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{

    public static $snakeAttributes = false;

    /**
     * Gets an attribute or the default.
     *
     * @param $key
     * @param null $defaultValue
     * @return null
     */
    protected function getAttributeOrDefault($key, $defaultValue = null)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $defaultValue;
    }

    /**
     * Retrieves a boolean accessor.
     *
     * @param $value
     * @return bool
     */
    protected function getBooleanAccessor($value)
    {
        return $value == 1;
    }

    /**
     * Sets a boolean mutator based on value.
     *
     * @param $field
     * @param $value
     */
    protected function setBooleanMutator($field, $value)
    {
        $this->attributes[$field] = ($value ? 1 : 0);
    }

}
<?php

namespace App\Enum;

abstract class Enum
{
    private $value;

    public function __get($property)
    {
        return $this->value;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }

    public function __isset($name)
    {
        return property_exists(static::class, $name);
    }
}

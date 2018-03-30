<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 26.03.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\ArrayContainer;


class Path
{
    protected $nodeName = null;
    protected $value = [];
    protected $defaultValue = null;
    protected $parent = null;

    public function __construct($value = [], $parent = null, $nodeName = null)
    {
        $this->value = $value;
        $this->parent = $parent;
        $this->nodeName = $nodeName;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->value)) {
            $value = $this->value[$name];
        } else {
            $value = [];
        }
        $child = new Path($value, $this, $name);
        return $child;
    }

    public function __set($name, $value)
    {
        if (!$this->value) {
            $this->value = [];
        } else if (!is_array($this->value)) {
            $this->value = [$this->value];
        }
        $this->value[$name] = $value;
        if ($this->parent) {
            $this->parent->{$this->nodeName} = $this->value;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        if (is_array($this->value)) {
            return json_encode($this->value, JSON_UNESCAPED_UNICODE);
        }
        return (string)$this->value;
    }

}
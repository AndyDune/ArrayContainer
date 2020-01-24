<?php
/**
 *
 * PHP version >= 7.1
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2020 Andrey Ryzhov
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
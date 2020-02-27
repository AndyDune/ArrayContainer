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


class ArraysAccumulator
{
    protected $data = [];

    protected $unique = false;

    protected $notEmpty = false;

    public function add($key, $value)
    {
        if (!array_key_exists($key, $this->data)) {
            $this->data[$key] = [];
        }

        if ($this->notEmpty and !(is_array($value) or is_object($value))) {
            $value = trim($value);
            if (!$value) {
                return $this;
            }
        }

        if ($this->unique) {
            if (in_array($value, $this->data[$key])) {
                return $this;
            }
        }

        $this->data[$key][] = $value;
        return $this;
    }

    public function count()
    {
        return count($this->data);
    }


    public function getKeys()
    {
        return array_keys($this->data);
    }

    public function getArrayCopy()
    {
        return $this->data;
    }

    /**
     * @param bool $unique
     * @return $this
     */
    public function setUnique(bool $unique): self
    {
        $this->unique = $unique;
        return $this;
    }

    /**
     * @param bool $notEmpty
     * @return $this
     */
    public function setNotEmpty(bool $notEmpty): self
    {
        $this->notEmpty = $notEmpty;
        return $this;
    }

}
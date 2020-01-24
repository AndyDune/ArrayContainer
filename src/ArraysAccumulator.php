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


    public function add($key, $value)
    {
        if (!array_key_exists($key, $this->data)) {
            $this->data[$key] = [];
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

}
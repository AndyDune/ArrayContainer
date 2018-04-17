<?php
/**
 *
 * PHP version >= 7.1
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer;


class ValuesAccumulator
{
    protected $maxLength = null;
    protected $values = [];
    protected $push = true;

    /**
     * @param $value
     * @param null $key use it on your risk
     * @return ValuesAccumulator
     */
    public function add($value, $key = null)
    {

        if ($this->push) {
            // experiment usage
            if ($key) {
                $this->values[$key] = $value;
            } else {
                $this->values[] = $value;
            }
            if ($this->maxLength and count($this->values) > $this->maxLength) {
                array_shift($this->values);
            }
        } else {
            array_unshift($this->values, $value);
            if ($this->maxLength and count($this->values) > $this->maxLength) {
                array_pop($this->values);
            }
        }
        return $this;
    }


    /**
     * Returns accumulated array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->values;
    }

    /**
     * @param integer $length
     * @return ValuesAccumulator
     */
    public function setMaxLength($length)
    {
        $this->maxLength = $length;
        return $this;
    }

    /**
     * Push value onto the end of array.
     * It is used as default.
     *
     * @return ValuesAccumulator
     */
    public function useArrayPush()
    {
        $this->push = true;
        return $this;
    }

    /**
     * Prepend value to the beginning of an array
     * @return ValuesAccumulator
     */
    public function useArrayUnShift()
    {
        $this->push = false;
        return $this;
    }

}
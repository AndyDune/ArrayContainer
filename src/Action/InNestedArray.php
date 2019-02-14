<?php
/**
 *
 * PHP version >= 7.1
 *
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class InNestedArray extends AbstractAction
{
    protected $value;
    protected $strongType;

    protected $valueMutator = false;

    public function __construct($value, $strongType = false)
    {
        $this->value = $value;
        $this->strongType = $strongType;
    }

    public function execute(...$params)
    {
        $array = $this->arrayContainer->getArrayCopy();

        return $this->isInArray($array);
    }

    /**
     * Set callback function for changing values before compare.
     *
     * @param callable $callback
     * @return InNestedArray
     */
    public function setValueMutator(callable $callback) : self
    {
        $this->valueMutator = $callback;
        $this->value = ($this->valueMutator)($this->value);
        return $this;
    }

    protected function isInArray($array)
    {

        foreach ($array as $arrayItem) {
            if (is_array($arrayItem)) {
                $result = $this->isInArray($arrayItem);
                if ($result) {
                    return true;
                }
                continue;
            }

            if ($this->valueMutator) {
                $arrayItem = ($this->valueMutator)($arrayItem);
            }

            if ($this->strongType) {
                if ($arrayItem === $this->value) {
                    return true;
                }
                return false;
            }

            if ($arrayItem == $this->value) {
                return true;
            }

        }
        return false;
    }

}
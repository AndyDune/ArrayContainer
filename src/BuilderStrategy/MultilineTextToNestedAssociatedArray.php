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

namespace AndyDune\ArrayContainer\BuilderStrategy;


use AndyDune\ArrayContainer\ArraysAccumulator;
use AndyDune\ArrayContainer\BuilderStrategy\Tool\MultilineTextExecuteTrait;

class MultilineTextToNestedAssociatedArray extends StrategyAbstract
{
    use MultilineTextExecuteTrait;
    protected $keyValueSeparator;

    protected $nestedArraySeparator;

    protected $arrayAccumulator;

    public function __construct($keyValueSeparator = '>', $nestedArraySeparator = ',')
    {
        $this->nestedArraySeparator = $nestedArraySeparator;
        $this->keyValueSeparator = $keyValueSeparator;
        $this->arrayAccumulator = (new ArraysAccumulator())->setNotEmpty(true)->setUnique(true);
    }

    protected function checkResultArray($array)
    {
        return $this->arrayAccumulator->getArrayCopy();
    }

    protected function explodeLine($line)
    {
        $parts = explode($this->keyValueSeparator, $line);
        if (count($parts) == 1) {
            return;
        }

        $key = trim(array_shift($parts));
        $value = trim(implode($this->keyValueSeparator, $parts));
        if (!$key) {
            return;
        }

        $parts = explode($this->nestedArraySeparator, $value);
        if (!$parts) {
            return;
        }

        foreach ($parts as $part) {
            $this->arrayAccumulator->add($key, $part);
        }
    }



}
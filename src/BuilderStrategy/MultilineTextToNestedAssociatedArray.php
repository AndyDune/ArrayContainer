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

    protected $allowEmpty = true;

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
        if (!$parts) {
            return;
        }

        $key = trim(array_shift($parts));

        if (!$key) {
            return;
        }

        if (!$parts) {
            if ($this->allowEmpty) {
                $this->arrayAccumulator->addKey($key);
            }
            return;
        }

        $value = trim(implode($this->keyValueSeparator, $parts));

        $parts = explode($this->nestedArraySeparator, $value);
        if (!$parts) {
            return;
        }

        foreach ($parts as $part) {
            $this->arrayAccumulator->add($key, $part);
        }
    }

    /**
     * @param bool $allowEmpty
     * @return $this
     */
    public function setAllowEmpty(bool $allowEmpty): self
    {
        $this->allowEmpty = $allowEmpty;
        return $this;
    }
}
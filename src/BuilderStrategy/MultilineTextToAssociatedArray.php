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


use AndyDune\ArrayContainer\BuilderStrategy\Tool\MultilineTextExecuteTrait;

class MultilineTextToAssociatedArray extends StrategyAbstract
{
    use MultilineTextExecuteTrait;
    protected $keyValueSeparator;

    protected $handleMatchingKeys = false;

    public function __construct($keyValueSeparator = '>')
    {
        $this->keyValueSeparator = $keyValueSeparator;
    }

    protected function explodeLine($line)
    {
        $parts = explode($this->keyValueSeparator, $line);
        if (count($parts) == 1) {
            $this->addToArray($parts[0], null);
            return;
        }
        $key = trim(array_shift($parts));
        $value = trim(implode($this->keyValueSeparator, $parts));
        if (!$key) {
            $this->array[] = $value;
            return;
        }
        $this->addToArray($key, $value);
    }

    protected function addToArray($key, $value)
    {
        if ($this->handleMatchingKeys and array_key_exists($key, $this->array)) {
            if (!is_array($this->array[$key])) {
                $this->array[$key] = [$this->array[$key]];
            }
            $this->array[$key][] = $value;
        } else {
            $this->array[$key] = $value;
        }
    }

    /**
     * @param bool $handleMatchingKeys
     * @return $this
     */
    public function setHandleMatchingKeys(bool $handleMatchingKeys): self
    {
        $this->handleMatchingKeys = $handleMatchingKeys;
        return $this;
    }

}
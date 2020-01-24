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


class MultilineTextToAssociatedArray extends StrategyAbstract
{
    private $keyValueSeparator;

    private $array = [];

    public function __construct($keyValueSeparator = '>')
    {
        $this->keyValueSeparator = $keyValueSeparator;
    }

    public function execute(): array
    {
        $this->array = [];
        $text = trim($this->builder->getSource());
        $lineSeparator = "\r\n";
        if (!preg_match('|' . $lineSeparator . '|ui', $text)) {
            $lineSeparator = "\n";
        }
        foreach (explode($lineSeparator, $text) as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            $this->explodeLine($line);
        }
        return $this->array;
    }

    protected function explodeLine($line)
    {
        $parts = explode($this->keyValueSeparator, $line);
        if (count($parts) == 1) {
            $this->array[$parts[0]] = null;
            return;
        }
        $key = trim(array_shift($parts));
        $value = trim(implode($this->keyValueSeparator, $parts));
        if (!$key) {
            $this->array[] = $value;
            return;
        }
        $this->array[$key] = $value;
    }
}
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

class StringExplode extends StrategyAbstract
{
    protected $separator;
    protected $noEmptyValues = true;

    public function __construct(string $separator = ',', $noEmptyValues = true)
    {
        $this->separator = $separator;
        $this->noEmptyValues = $noEmptyValues;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        $parts = explode($this->separator, $this->builder->getSource());
        $result = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if (!$part and $this->noEmptyValues) {
                continue;
            }
            $result[] = $part;
        }
        return  $result;
    }
}
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


use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToAssociatedArray;
use AndyDune\ArrayContainer\BuilderStrategy\StrategyAbstract;

class Builder
{
    /**
     * @var mixed
     */
    protected $source;

    protected $result = [];

    protected $strategy;

    public function __construct($source, ?StrategyAbstract $strategy= null)
    {
        $this->source = $source;
        if (!$strategy) {
            $strategy = new MultilineTextToAssociatedArray();
        }
        $this->strategy = $strategy;
    }


    public function execute()
    {
        return $this->strategy->setBuilder($this)->execute();
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return $this
     */
    public function setSource($source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param array $result
     * @return $this
     */
    public function setResult(array $result): self
    {
        $this->result = $result;
        return $this;
    }

}
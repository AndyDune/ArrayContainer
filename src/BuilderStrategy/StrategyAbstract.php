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


use AndyDune\ArrayContainer\Builder;

abstract class StrategyAbstract
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * @param Builder $builder
     * @return $this
     */
    public function setBuilder(Builder $builder): self
    {
        $this->builder = $builder;
        return $this;
    }


    abstract public function execute(): array;
}
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



namespace AndyDune\ArrayContainer\Action;
use AndyDune\ArrayContainer\ArrayContainer;

abstract class AbstractAction
{
    /**
     * @var ArrayContainer
     */
    protected $arrayContainer;

    public function setArrayContainer(ArrayContainer $container)
    {
        $this->arrayContainer = $container;
    }

    abstract public function execute(...$params);

}
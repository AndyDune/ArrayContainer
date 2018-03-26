<?php
/**
 *
 * PHP version >= 7.1
 *
 * Array ['one' => 'low', 'ONE' => 'high']
 * result to
 * ['one' => 'high']
 *
 * Array ['ONE' => 'high', 'one' => 'low']
 * result to
 * ['one' => 'low']
 *
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class KeysToLower extends AbstractAction
{
    public function execute(...$params)
    {
        $arrayCopy = $this->arrayContainer->getArrayCopy();
        $result = [];
        foreach($arrayCopy as $key => $value) {
            $key = strtolower($key);
            $result[$key] = $value;
        }
        $this->arrayContainer->setArray($result);
    }
}
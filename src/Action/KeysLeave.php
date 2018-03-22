<?php
/**
 *
 * PHP version 7.X
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class KeysLeave extends AbstractAction
{
    public function execute(...$params)
    {
        $param1 = $params[0] ?? [];
        if (is_array($param1)) {
            $params = $param1;
        }
        $arrayCopy = $this->arrayContainer->getArrayCopy();
        $this->arrayContainer->setArray(
        array_filter($arrayCopy, function ($key) use ($params) {
            if (in_array($key, $params)) {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_KEY));

    }
}
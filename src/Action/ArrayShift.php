<?php
/**
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class ArrayShift extends AbstractAction
{
    public function execute(...$params)
    {
        $value = $this->arrayContainer->getArrayCopy();
        $result = [];
        $firstValue = true;

        $value = array_filter($value, function ($value, $key) use (&$firstValue, &$result) {
            if ($firstValue) {
                $firstValue = false;
                $result = [$key => $value];
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $this->arrayContainer->setArray($value);
        return $result;
    }
}
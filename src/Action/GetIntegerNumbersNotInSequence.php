<?php
/**
 * It checks source array if it has only this keys.
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */

namespace AndyDune\ArrayContainer\Action;


class GetIntegerNumbersNotInSequence extends AbstractAction
{
    /**
     * @param array ...$arrays
     * @return array
     */
    public function execute(...$arrays): array
    {
        $array = $this->arrayContainer->getArrayCopy();
        sort($array, SORT_NUMERIC);

        $resultArray = [];

        $waitNextValue = (int)array_shift($array) + 1;
        while (($value = array_shift($array)) !== null) {
            $resultArray = array_merge($resultArray, $this->getSkipped($waitNextValue, (int)$value));
            $waitNextValue = (int)$value + 1;
        }
         return $resultArray;
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    protected function getSkipped($from, $to)
    {
        if ($from == $to or $from > $to) {
            return [];
        }
        return range($from, $to - 1);
    }
}
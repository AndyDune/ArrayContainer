<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 20.04.2018                            |
 * -----------------------------------------------
 *
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
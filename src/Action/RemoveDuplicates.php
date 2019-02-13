<?php
/**
 *
 * PHP version >= 7.1
 *
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class RemoveDuplicates extends AbstractAction
{

    public function execute(...$params)
    {
        $array = $this->arrayContainer->getArrayCopy();

        $duplicatesCount = 0;
        $newArray = [];
        foreach ($array as $key => $value) {
            if (in_array($value, $newArray)) {
                $duplicatesCount++;
                continue;
            }
            $newArray[$key] = $value;
        }

        if ($duplicatesCount) {
            $this->arrayContainer->setArray($newArray);
        }

        return $duplicatesCount;
    }

}
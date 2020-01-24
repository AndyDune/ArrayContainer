<?php
/**
 * It searches max float value in the given array. Each value is prepared by removing spaces.
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2020 Andrey Ryzhov
 */

namespace AndyDune\ArrayContainer\Action;


class FindMaxFloatValue extends AbstractAction
{
    /**
     * @param array ...$params
     * @return float
     */
    public function execute(...$params): ?float
    {
        return  array_reduce($this->arrayContainer->getArrayCopy(), function ($carry, $item) {
            $item = preg_replace('#\s#', '', $item);
            $item = (float)$item;
            if ($item > $carry or $carry === null) {
                return $item;
            }
            return $carry;
        });
    }

}
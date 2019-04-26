<?php
/**
 * It concat arrays for its values. It not saves keys.
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class Concat extends AbstractAction
{
    /**
     * @param array ...$arrays
     * @return array
     */
    public function execute(...$arrays): array
    {
        foreach ($arrays as $ak => $av) {
            if (is_array($av)) {
                $arrays[$ak] = array_values($av);
            } else {
                $arrays[$ak] = [$av];
            }
        }
        $array = call_user_func_array('array_merge', $arrays);
        $array = array_merge($this->arrayContainer->getArrayCopy(), $array);
        $this->arrayContainer->setArray($array);
        return $array;
    }
}
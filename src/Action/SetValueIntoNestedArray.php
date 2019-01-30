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


class SetValueIntoNestedArray extends AbstractAction
{
    private $value = null;
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function execute(...$params)
    {
        if (!$params) {
            return false;
        }
        $array = $this->arrayContainer->getArrayCopy();

        $arrayToReplace = $this->setValue($params, []);
        $array = array_replace_recursive($array, $arrayToReplace);
        $this->arrayContainer->setArray($array);

    }

    protected function setValue($keys, $array)
    {
        $keyToUse = array_shift($keys);
        if (!$keys) {
            $array[$keyToUse] = $this->value;
        } else {
            $array[$keyToUse] = $this->setValue($keys, []);
        }
        return $array;
    }

}
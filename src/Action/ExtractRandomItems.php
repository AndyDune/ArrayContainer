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


class ExtractRandomItems extends AbstractAction
{
    private $count = null;
    private $firstElementRequire = null;
    private $lastElementRequire = null;

    public function __construct($count = 1, $firstElementRequire = false, $lastElementRequire = false)
    {
        $this->count = $count;
        $this->firstElementRequire = $firstElementRequire;
        $this->lastElementRequire = $lastElementRequire;
    }

    public function execute(...$params)
    {
        $array = $this->arrayContainer->getArrayCopy();
        if (!$array or $this->count < 1) {
            return [];
        }

        $arrayResult = [];

        $count = $this->count;

        if ($this->firstElementRequire) {
            $count--;
            $key = key($array);
            $arrayResult[$key] = current($array);
            unset($array[$key]);
        }

        if (!$array or !$count) {
            return $arrayResult;
        }

        for ($rest = $count; $rest > 0; $rest--) {
            if (!$array) {
                break;
            }
            $keys = array_keys($array);
            $keyNumber = rand(0, count($keys) - 1);
            $key = $keys[$keyNumber];
            $arrayResult[$key] = $array[$key];
            unset($array[$key]);
        }

        return $arrayResult;

    }


}
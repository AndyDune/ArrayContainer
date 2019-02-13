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

    public function __construct($count = 1, $firstElementRequire = false)
    {
        $this->count = $count;
        $this->firstElementRequire = $firstElementRequire;
    }

    public function execute(...$params)
    {
        $array = $this->arrayContainer->getArrayCopy();
        if (!$array or $this->count < 1) {
            return [];
        }

        $arrayResult = [];

        if ($this->firstElementRequire) {
            $this->count--;
            $key = key($array);
            $arrayResult[$key] = current($array);
            unset($array[$key]);
        }

        if (!$array or !$this->count) {
            return $arrayResult;
        }

        for ($rest = $this->count; $rest > 0; $rest--) {
            $keys = array_keys($array);
            $keyNumber = rand(0, count($keys) - 1);
            $key = $keys[$keyNumber];
            $arrayResult[$key] = $array[$key];
            unset($array[$key]);
        }

        return $arrayResult;

    }


}
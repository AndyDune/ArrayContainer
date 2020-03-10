<?php
/**
 *
 * PHP version >= 7.1
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2020 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class BringToView extends AbstractAction
{
    /**
     * @var array
     */
    protected $pattern;

    public function __construct(array $pattern)
    {
        $this->pattern = $pattern;
    }

    public function execute(...$params)
    {
        $array = $this->arrayContainer->getArrayCopy();
        foreach($this->pattern as $key => $value) {
            $array[$key] = $this->handle($value, $array[$key] ?? null);
        }
        $this->arrayContainer->setArray($array);
    }

    protected function handle($patternValue, $array)
    {
        if (is_array($patternValue)) {
            if (!is_array($array)) {
                $array = [];
            }
            foreach ($patternValue as $key => $value) {
                $array[$key] = $this->handle($value, $array[$key] ?? null);
            }
            return $array;
        }

        if (in_array($patternValue, ['str', 'string'])) {
            return $this->toString($array);
        }

        if (in_array($patternValue, ['integer', 'int'])) {
            return (int)$array;
        }

        return $array;
    }

    protected function toString($value)
    {
        if (is_array($value)) {
            return 'array';
        }

        if (is_object($value)) {
            return 'object';
        }
        return (string)$value;
    }
}
<?php
/**
 * Computes the difference of arrays with additional index check.
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */

namespace AndyDune\ArrayContainer\Action;

class ComputeDifferenceOfArrays extends AbstractAction
{

    protected $keysToIgnore = [];

    /**
     * @param array ...$arrays
     * @return array
     */
    public function execute(...$arrays): array
    {
        $arrayResult = $this->arrayContainer->getArrayCopy();

        foreach ($arrays as $array) {
            $arrayResult = $this->diff($arrayResult, $array);
        }

        return $arrayResult;
    }

    /**
     * @param mixed ...$keys
     * @return ComputeDifferenceOfArrays
     */
    public function ignoreKeys(...$keys): self
    {
        $this->keysToIgnore = [];
        foreach ($keys as $keyToIgnore) {
            if (is_array($keyToIgnore)) {
                $this->keysToIgnore = array_merge($this->keysToIgnore, $keyToIgnore);
                continue;
            }
            $this->keysToIgnore[] = $keyToIgnore;
        }
        return $this;
    }

    protected function diff($array1, $array2)
    {
        if (!is_array($array2)) {
            $array2 = [];
        }
        $arrayResult = [];
        foreach ($array1 as $key => $value) {
            if (in_array($key, $this->keysToIgnore)) {
                continue;
            }
            if (is_array($value)) {
                $value = $this->diff($value, $array2[$key] ?? []);
                if ($value) {
                    $arrayResult[$key] = $value;
                }
                continue;
            }

            if (!array_key_exists($key, $array2)) {
                $arrayResult[$key] = $value;
                continue;
            }

            if ($array2[$key] == $value) {
                continue;
            }
            $arrayResult[$key] = $value;
        }

        return $arrayResult;
    }
}
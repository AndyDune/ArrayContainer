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


class IsOnlyThisKeysInArray extends AbstractAction
{

    protected $keys = [];

    /**
     * @param array ...$arrays
     * @return bool
     */
    public function execute(...$arrays): bool
    {
        if ($arrays) {
            $this->setKeys($arrays);
        }

        $array = $this->arrayContainer->getArrayCopy();
        foreach ($array as $key => $value) {
            if (in_array($key, $this->keys)) {
                continue;
            }
            return false;
        }
        return true;
    }

    protected function setKeys($keys): self
    {
        $this->keys = [];
        foreach ($keys as $keyToSet) {
            if (is_array($keyToSet)) {
                $this->keys = array_merge($this->keys, $keyToSet);
                continue;
            }
            $this->keys[] = $keyToSet;
        }
        return $this;
    }

}
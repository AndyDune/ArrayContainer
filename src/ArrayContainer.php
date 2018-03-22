<?php
/**
 *
 * PHP version 7.X
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer;


class ArrayContainer implements \ArrayAccess
{
    protected $array = [];
    protected $defaultValue = null;

    public function __construct($array = [])
    {
        $this->array = $array;
    }

    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
    }

    /**
     * Access to value if source array is nested.
     *
     * @param $keyString
     * @param null $default default value if no nested keys has been matched
     * @param string $separator
     * @return array|mixed|null
     */
    public function getNested($keyString, $default = null, $separator = '.')
    {
        $keys = explode($separator, $keyString);
        $data = $this->array;
        foreach ($keys as $key) {
            if(!is_array($data) or !array_key_exists($key, $data)) {
                return $default;
            }
            $data = $data[$key];
        }
        return $data;
    }

    public function get($offset)
    {
        if (array_key_exists($offset, $this->array)) {
            return $this->applyFilters($this->array[$offset]);
        }
        return $this->defaultValue;
    }

    public function set($offset, $value)
    {
        $this->array[$offset] = $value;
        return $this;
    }

    /**
     * @todo in the making
     *
     * @param $value
     * @return mixed
     */
    protected function applyFilters($value)
    {
        return $value;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->array);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->array)) {
            unset($this->array[$offset]);
        }

    }
}
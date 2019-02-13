<?php
/**
 *
 * PHP version >= 7.1
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\ArrayContainer\Action;


class KeysAddIfNoExist extends AbstractAction
{

    private $value = null;
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function execute(...$params)
    {
        $count = 0;
        $param1 = $params[0] ?? [];
        if (is_array($param1)) {
            $params = $param1;
        }
        $container = $this->arrayContainer;
        array_walk($params, function ($value, $key) use ($container, &$count) {
            if (!$container->has($value)) {
                $count++;
                $container->set($value, $this->value);
            }
        });
    }
}
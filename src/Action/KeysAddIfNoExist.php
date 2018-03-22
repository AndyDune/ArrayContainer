<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 22.03.2018                            |
 * -----------------------------------------------
 *
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
        $param1 = $params[0] ?? [];
        if (is_array($param1)) {
            $params = $param1;
        }
        $container = $this->arrayContainer;
        array_walk($params, function ($value, $key) use ($container) {
            if (!$container->has($value)) {
                $container->set($value, $this->value);
            }
        });
    }
}
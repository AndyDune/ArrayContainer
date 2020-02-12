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


namespace AndyDune\ArrayContainer\BuilderStrategy;

use AndyDune\ArrayContainer\BuilderStrategy\Tool\MultilineTextExecuteTrait;

class MultilineTextAsJsonToAssociatedArray extends StrategyAbstract
{
    use MultilineTextExecuteTrait;

    protected function explodeLine($line)
    {
        $line = rtrim($line, ", \t\n\r\0\x0B");
        $matches = [];
        $find = preg_match('|^"(.+)"\s*:\s*(.*)|u', $line, $matches);
        if (!$find) {
            return;
        }
        $matches[2] = trim($matches[2]);
        if ($matches[2] == 'null') {
            $matches[2] = null;
        } elseif (preg_match('|^[\d]$|u', $matches[2])) {
            $matches[2] = (int)$matches[2];
        } else {
            $matches[2] = trim($matches[2], '"');
        }
        $this->array[$matches[1]] = $matches[2];
    }
}
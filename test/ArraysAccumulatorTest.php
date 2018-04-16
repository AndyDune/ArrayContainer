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

namespace AndyDuneTest\ArrayContainer;
use AndyDune\ArrayContainer\ArraysAccumulator;
use PHPUnit\Framework\TestCase;


class ArraysAccumulatorTest extends TestCase
{
    public function testAccumulator()
    {
        $accumulator = new ArraysAccumulator();
        $accumulator->add('one', 'bublic');
        $accumulator->add('one', ['bublic', '2']);
        $accumulator->add('two', 'ice-cream');
        $accumulator->add('two', ['ice-cream', '3']);

        $this->assertEquals(2, $accumulator->count());
        $this->assertEquals(['one', 'two'], $accumulator->getKeys());

        $array = $accumulator->getArrayCopy();
        $this->assertTrue(array_key_exists('one', $array));
        $this->assertEquals(['bublic', ['bublic', '2']], $array['one']);
        $this->assertTrue(array_key_exists('two', $array));
        $this->assertEquals(['ice-cream', ['ice-cream', '3']], $array['two']);
    }

}
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
use AndyDune\ArrayContainer\ValuesAccumulator;
use PHPUnit\Framework\TestCase;


class AccumulatorTest extends TestCase
{
    public function testArraysAccumulator()
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

    public function testValuesAccumulator()
    {
        $accumulator = new ValuesAccumulator();
        $accumulator->add('one')
            ->add('two')->add(3)->add(4);
        $array = $accumulator->getArrayCopy();
        $this->assertCount(4, $array);
        $this->assertEquals('one', array_shift($array));
        $this->assertEquals('two', array_shift($array));
        $this->assertEquals(3, array_shift($array));
        $this->assertEquals(4, array_shift($array));

        $accumulator = new ValuesAccumulator();
        $accumulator->useArrayUnShift();
        $accumulator->add('one')
            ->add('two')->add(3)->add(4);
        $array = $accumulator->getArrayCopy();
        $this->assertCount(4, $array);
        $this->assertEquals(4, array_shift($array));
        $this->assertEquals(3, array_shift($array));
        $this->assertEquals('two', array_shift($array));
        $this->assertEquals('one', array_shift($array));


        $accumulator = new ValuesAccumulator();
        $accumulator->setMaxLength(2);
        $accumulator->add('one')
            ->add('two')->add(3)->add(4);
        $array = $accumulator->getArrayCopy();
        $this->assertCount(2, $array);
        $this->assertEquals(3, array_shift($array));
        $this->assertEquals(4, array_shift($array));

        $accumulator = new ValuesAccumulator();
        $accumulator->useArrayUnShift();
        $accumulator->setMaxLength(2);
        $accumulator->add('one')
            ->add('two')->add(3)->add(4);
        $array = $accumulator->getArrayCopy();
        $this->assertCount(2, $array);
        $this->assertEquals(4, array_shift($array));
        $this->assertEquals(3, array_shift($array));

    }
}
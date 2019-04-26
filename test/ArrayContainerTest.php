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

use AndyDune\ArrayContainer\Action\ArrayShift;
use AndyDune\ArrayContainer\Action\Concat;
use AndyDune\ArrayContainer\Action\ExtractRandomItems;
use AndyDune\ArrayContainer\Action\InNestedArray;
use AndyDune\ArrayContainer\Action\KeysAddIfNoExist;
use AndyDune\ArrayContainer\Action\KeysLeave;
use AndyDune\ArrayContainer\Action\KeysToLower;
use AndyDune\ArrayContainer\Action\RemoveDuplicates;
use AndyDune\ArrayContainer\Action\SetValueIntoNestedArray;
use AndyDune\ArrayContainer\ArrayContainer;
use PHPUnit\Framework\TestCase;

class ArrayContainerTest extends TestCase
{
    public function testSetGetDefault()
    {
        $array = new ArrayContainer();
        $this->assertEquals(null, $array['tt']);
        $this->assertEquals(null, $array->get('tt'));
        $array->setDefaultValue('NO');
        $this->assertEquals('NO', $array['tt']);
        $this->assertEquals('NO', $array->get('tt'));

        $array['tt'] = 'YES';
        $this->assertEquals('YES', $array['tt']);
        $this->assertEquals('YES', $array->get('tt'));

        $array->set('tt', 'Y');
        $this->assertEquals('Y', $array['tt']);
        $this->assertEquals('Y', $array->get('tt'));
    }

    public function testFilterCallback()
    {
        $array = new ArrayContainer(['a' => 'B']);
        $this->assertEquals('B', $array['a']);
        $array->addFilter(function ($value) {
            return strtolower($value);
        });
        $this->assertEquals('b', $array['a']);


        $keyToDo = 'a';
        $array = new ArrayContainer(['a' => 'B', 'f' => 'Z']);
        $this->assertEquals('B', $array['a']);
        $array->addFilter(function ($value, $key) use ($keyToDo) {
            if ($keyToDo == $key) {
                return strtolower($value);
            }
            return $value;
        });
        $this->assertEquals('b', $array['a']);
        $this->assertEquals('Z', $array['f']);

    }

    public function testActionKeysLeave()
    {
        $arraySource = ['a' => 1, 'b' => 2, 'c' => 3];
        $container = new ArrayContainer($arraySource);
        $container->setAction(new KeysLeave())->executeAction('a', 'c');
        $this->assertTrue($container->has('a'));
        $this->assertTrue($container->has('c'));
        $this->assertFalse($container->has('b'));

        $container = new ArrayContainer($arraySource);
        $container->setAction(new KeysLeave())->executeAction(['a', 'c']);
        $this->assertTrue($container->has('a'));
        $this->assertTrue($container->has('c'));
        $this->assertFalse($container->has('b'));

        $container = new ArrayContainer($arraySource);
        $container->setAction(new KeysLeave())->executeAction('b');
        $this->assertFalse($container->has('a'));
        $this->assertFalse($container->has('c'));
        $this->assertTrue($container->has('b'));
    }

    public function testActionKeysAddIfNoExist()
    {
        $container = new ArrayContainer();
        $this->assertEquals(null, $container['a']);
        $this->assertEquals(null, $container['c']);

        $container->setAction(new KeysAddIfNoExist(1))->executeAction('a', 'c');
        $this->assertEquals(1, $container['a']);
        $this->assertEquals(1, $container['c']);
        $this->assertEquals(null, $container['b']);
    }

    public function testKeysToLower()
    {
        $container = new ArrayContainer(['one' => 'low', 'ONE' => 'high']);
        $this->assertEquals('low', $container['one']);
        $this->assertEquals('high', $container['ONE']);

        $container->setAction(new KeysToLower())->executeAction();
        $this->assertEquals('high', $container['one']);
        $this->assertEquals(null, $container['ONE']);


        $container = new ArrayContainer(['ONE' => 'high', 'one' => 'low']);
        $container->setAction(new KeysToLower())->executeAction();
        $this->assertEquals('low', $container['one']);
        $this->assertEquals(null, $container['ONE']);

    }

    public function testShiftArray()
    {
        $arraySource = [
            40 => 'fourty',
            50 => 'figty',
            60 => 'sixty',
        ];
        $container = new ArrayContainer($arraySource);

        // how it works
        $result = array_shift($arraySource);
        $this->assertEquals('fourty', $result);
        $this->assertArrayNotHasKey(40, $arraySource);
        $this->assertArrayNotHasKey(50, $arraySource);

        $result = $container->setAction(new ArrayShift())->executeAction();
        $this->assertEquals([40 => 'fourty'], $result);

        $arrayInContainer = $container->getArrayCopy();
        $this->assertCount(2, $arrayInContainer);
        $this->assertArrayHasKey(50, $arrayInContainer);
        $this->assertArrayHasKey(60, $arrayInContainer);
    }

    public function testActionSetValueIntoNestedArray()
    {
        $array = [
            'a' => 1,
            'b' => [
                'c' => 2
            ]
        ];
        $container = new ArrayContainer($array);
        $array['a1'] = 3;
        $container->setAction(new SetValueIntoNestedArray(3))->executeAction('a1');
        $this->assertEquals($array, $container->getArrayCopy());

        $array['a2'] = ['a2' => 12];
        $container->setAction(new SetValueIntoNestedArray(12))->executeAction('a2', 'a2');
        $this->assertEquals($array, $container->getArrayCopy());

        $array['a'] = 10;
        $container->setAction(new SetValueIntoNestedArray(10))->executeAction('a');
        $this->assertEquals($array, $container->getArrayCopy());

        $array['b']['cc'] = 22;
        $container->setAction(new SetValueIntoNestedArray(22))->executeAction('b', 'cc');
        $this->assertEquals($array, $container->getArrayCopy());

        $array['b']['ccc'] = ['cccc' => 23];
        $container->setAction(new SetValueIntoNestedArray(23))->executeAction('b', 'ccc', 'cccc');
        $this->assertEquals($array, $container->getArrayCopy());

        $this->assertEquals(2, $container->getArrayCopy()['b']['c']);


        $array['b']['c'] = ['r' => 23];
        $container->setAction(new SetValueIntoNestedArray(23))->executeAction('b', 'c', 'r');
        $this->assertEquals($array, $container->getArrayCopy());

        $this->assertEquals(['r' => 23], $container->getArrayCopy()['b']['c']);

    }

    public function testRemoveDuplicates()
    {
        $array = [
            'a' => 'a',
            'b' => 'b',
            'b1' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
            'f' => 'f',
        ];
        $container = new ArrayContainer($array);
        $count = $container->setAction(new RemoveDuplicates())->executeAction();
        $this->assertEquals(1, $count);
        $this->assertFalse(array_key_exists('b1', $container->getArrayCopy()));

    }

    public function testExtractRandomItems()
    {
        $array = [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f'
        ];
        $container = new ArrayContainer($array);
        $array1 = $container->setAction(new ExtractRandomItems(3))->executeAction();
        $containerResult = new ArrayContainer($array1);
        $this->assertEquals(0, $containerResult->setAction(new RemoveDuplicates())->executeAction());
        $array2 = $container->setAction(new ExtractRandomItems(3))->executeAction();
        $containerResult = new ArrayContainer($array2);
        $this->assertEquals(0, $containerResult->setAction(new RemoveDuplicates())->executeAction());

        $this->assertCount(3, $array1);
        $this->assertCount(3, $array2);

        $array1 = $container->setAction(new ExtractRandomItems(3, true))->executeAction();
        $containerResult = new ArrayContainer($array1);
        $this->assertEquals(0, $containerResult->setAction(new RemoveDuplicates())->executeAction());
        $this->assertCount(3, $array1);
        $this->assertEquals('a', $array1[0]);

        $array1 = $container->setAction(new ExtractRandomItems(3, true))->executeAction();
        $containerResult = new ArrayContainer($array1);
        $this->assertEquals(0, $containerResult->setAction(new RemoveDuplicates())->executeAction());
        $this->assertCount(3, $array1);
        $this->assertEquals('a', $array1[0]);

        // Create a new array with more elements then source array
        $array = [
            'a',
            'b',
        ];
        $container = new ArrayContainer($array);
        $array1 = $container->setAction(new ExtractRandomItems(6))->executeAction();
        $this->assertCount(2, $array1);

    }

    public function testInNestedArray()
    {
        $array = [
            'a' => 1,
            'b' => [
                'c' => 2
            ]
        ];
        $container = new ArrayContainer($array);
        $this->assertTrue($container->setAction(new InNestedArray(1))->executeAction());
        $this->assertTrue($container->setAction(new InNestedArray('1'))->executeAction());
        $this->assertTrue($container->setAction(new InNestedArray(2))->executeAction());
        $this->assertFalse($container->setAction(new InNestedArray(5))->executeAction());

        $this->assertFalse($container->setAction(new InNestedArray('1', true))->executeAction());


        $array = [
            [
                [
                    'name' => 'Ivan'
                ],
                [
                    'name' => 'Andrey'
                ],
            ]
        ];
        $container = new ArrayContainer($array);
        $this->assertTrue($container->setAction(new InNestedArray('Ivan'))->executeAction());
        $this->assertFalse($container->setAction(new InNestedArray('ivan'))->executeAction());

        $this->assertTrue($container->setAction((new InNestedArray('ivan'))->setValueMutator(function ($value) {
            if (!is_string($value)) {
                return $value;
            }
            return strtolower($value);
        }))->executeAction());

    }

    public function testConcatArray()
    {
        $array = [
            1,
            'two' => 2,
            3,
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new Concat())->executeAction([0 => 11, 'two' => 22]);
        $this->assertEquals([1, 'two' => 2, 3, 11, 22], $result);


        $array = [
            1,
            'two' => 2,
            3,
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new Concat())->executeAction([0 => 11, 'two' => 22], ['two' => 222]);
        $this->assertEquals([1, 'two' => 2, 3, 11, 22, 222], $result);

        $array = [
            1,
            'two' => 2,
            3,
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new Concat())->executeAction([0 => 11, 'two' => 22], 222);
        $this->assertEquals([1, 'two' => 2, 3, 11, 22, 222], $result);

    }


}
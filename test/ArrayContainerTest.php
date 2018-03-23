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


namespace AndyDuneTest\ArrayContainer;

use AndyDune\ArrayContainer\Action\KeysAddIfNoExist;
use AndyDune\ArrayContainer\Action\KeysLeave;
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
}
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
    }
}
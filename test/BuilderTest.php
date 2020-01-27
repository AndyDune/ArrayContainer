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

namespace AndyDuneTest\ArrayContainer;

use AndyDune\ArrayContainer\ArrayContainer;
use AndyDune\ArrayContainer\Builder;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextAsJsonToAssociatedArray;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToAssociatedArray;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    /**
     * @covers \AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToAssociatedArray::handle
     */
    public function testMultilineTextToAssociatedArray()
    {
        $text = '
        one => two
        three
        => four
        
        ';

        $expectResult = [
            'one' => 'two',
            'four',
            'three' => null
        ];

        $builder = new Builder($text, new MultilineTextToAssociatedArray('=>'));
        $this->assertEquals($expectResult, $builder->execute());
    }

    public function testMultilineTextAsJsonToAssociatedArray()
    {
        $text = '
        {
        "one":"two",
        "two" : 2,
        "three":null
        }
        
        ';

        $expectResult = [
            'one' => 'two',
            'two' => 2,
            'three' => null
        ];

        $json = json_encode($expectResult, JSON_UNESCAPED_UNICODE);

        $builder = new Builder($text, new MultilineTextAsJsonToAssociatedArray());
        $this->assertEquals($expectResult, $builder->execute());


    }
}
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
}
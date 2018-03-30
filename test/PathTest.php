<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 26.03.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDuneTest\ArrayContainer;
use AndyDune\ArrayContainer\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testBase()
    {
        $pathObject = new Path();
        $pathObject->one->two->three = 'four';

        $this->assertEquals(['one' => ['two' => ['three' => 'four']]], $pathObject->getValue());

        $pathObject->one->two->three->down = 'four';

        $this->assertEquals('four', (string)$pathObject->one->two->three->down);

        $this->assertEquals(['one' => ['two' => ['three' => [0 => 'four', 'down' => 'four']]]], $pathObject->getValue());
    }

}
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
use AndyDune\ArrayContainer\BuilderStrategy\MarkdownTableToArray;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextAsJsonToAssociatedArray;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToAssociatedArray;
use AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToNestedAssociatedArray;
use AndyDune\ArrayContainer\BuilderStrategy\StringExplode;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    /**
     * @covers \AndyDune\ArrayContainer\BuilderStrategy\MultilineTextToAssociatedArray::handle
     */
    public function testMultilineTextToAssociatedArray()
    {
        $text = '
        one => one
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

        $expectResult = [
            'one' => ['one', 'two'],
            'four',
            'three' => null
        ];

        $builder = new Builder($text, (new MultilineTextToAssociatedArray('=>'))
            ->setHandleMatchingKeys(true));
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

    public function testMarkdown()
    {
        $text = '
        | one | two | 
        | --- | ---
        1 | 2
        11
        | 12| 13 | 14
        ';

        $expectResult = [
            [
                'one' => 1,
                'two' => 2
            ],
            [
                'one' => '11',
                'two' => null
            ],
            [
                'one' => '12',
                'two' => '13'
            ]
        ];


        $text = '
        | one | two | 
         ||--
         |
        1 | 2
        11
        | 12| 13 | 14
        ';

        $expectResult = [
            [
                'one',
                'two'
            ],
            [
                '',
                '--'
            ],
            [
                '',
                ''
            ],
            [
                '1',
                '2'
            ],
            [
                '11',
                null
            ],
            [
                '12',
                '13'
            ]
        ];


        $builder = new Builder($text, new MarkdownTableToArray());
        $this->assertEquals($expectResult, $builder->execute());
    }

    public function testMultilineTextToNestedAssociatedArray()
    {
        $text = '
        one > one,
        one > two, one
        three
        > four
        four >         
        four > 4, 5     , 6
        ';

        $expectResult = [
            'one' => ['one', 'two'],
            'four' => [4, 5, 6],
            'three' => [],
        ];

        $builder = new Builder($text, new MultilineTextToNestedAssociatedArray());
        $this->assertEquals($expectResult, $builder->execute());

        $expectResult = [
            'one' => ['one', 'two'],
            'four' => [4, 5, 6],
        ];

        $builder = new Builder($text, (new MultilineTextToNestedAssociatedArray())->setAllowEmpty(false));
        $this->assertEquals($expectResult, $builder->execute());
    }

    public function testStringExplode()
    {
        $text = '
        one , two,
        ';

        $expectResult = [
            'one', 'two'
        ];

        $builder = new Builder($text, new StringExplode(','));
        $this->assertEquals($expectResult, $builder->execute());

        $expectResult = [
            'one', 'two', ''
        ];

        $builder = new Builder($text, new StringExplode(',', false));
        $this->assertEquals($expectResult, $builder->execute());

    }
}
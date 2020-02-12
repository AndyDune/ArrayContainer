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

class MarkdownTableToArray extends StrategyAbstract
{
    protected $countRows = 0;
    protected $firstRow = [];
    protected $firstRowColCount = 0;
    protected $useFirstRowAsKeys = false;
    protected $maxLengthCell = '';

    use MultilineTextExecuteTrait;

    protected function explodeLine($line)
    {
        $line = preg_replace('#^\|#', '', $line);
        $line = preg_replace('#\|$#', '', $line);
        $colls = $this->trimArrayItems(explode('|', $line));

        $this->countRows++;
        $countCells = count($colls);
        if ($this->countRows == 1) {
            $this->firstRowColCount = $countCells;
            $this->firstRow = $colls;
            return;
        }

        if ($this->countRows == 2) {
            if (preg_match('|-{3,}|', $this->findCellWithMaxLength($colls))) {
                $this->useFirstRowAsKeys = true;
                return;
            }
        }

        if ($countCells > $this->firstRowColCount) {
            $colls = array_slice($colls, 0, $this->firstRowColCount);
        } else if ($countCells < $this->firstRowColCount) {
            $colls = array_merge($colls, array_fill(0, $this->firstRowColCount - $countCells, null));
        }

        if ($this->useFirstRowAsKeys) {
            $this->array[] = array_combine($this->firstRow, $colls);
        } else {
            $this->array[] = $colls;
        }
    }

    protected function trimArrayItems($items)
    {
        array_walk($items, function (&$item, $key) {
            $item = trim($item);
        });
        return $items;
    }

    protected function findCellWithMaxLength($items)
    {
        $maxCell = '';
        $length = 0;
        array_walk($items, function ($item, $key) use (&$length, &$maxCell) {
            $lengthCurrent = strlen($item);
            if ($lengthCurrent <= $length) {
                return;
            }
            $length = $lengthCurrent;
            $maxCell = $item;
        });
        return $maxCell;
    }

    protected function checkResultArray($array)
    {
        if (!$this->useFirstRowAsKeys) {
            array_unshift($array, $this->firstRow);
        }
        return $array;
    }

}
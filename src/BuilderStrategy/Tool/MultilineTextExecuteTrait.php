<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 27.01.2020                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\ArrayContainer\BuilderStrategy\Tool;


trait MultilineTextExecuteTrait
{
    protected $array = [];

    public function execute(): array
    {
        $this->array = [];
        $text = trim($this->builder->getSource());
        $lineSeparator = "\r\n";
        if (!preg_match('|' . $lineSeparator . '|ui', $text)) {
            $lineSeparator = "\n";
        }
        foreach (explode($lineSeparator, $text) as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            $this->explodeLine($line);
        }
        return $this->checkResultArray($this->array);
    }

    protected function explodeLine($line)
    {

    }

    protected function checkResultArray($array)
    {
        return $array;
    }

}
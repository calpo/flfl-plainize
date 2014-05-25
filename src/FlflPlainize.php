<?php
namespace FlflPlainize;

use FlflPlainize\Line;
use FlflPlainize\Exception\InvalidLineException;

use Iterator;

class FlflPlainize
{
    private $input;
    private $outputter;

    public function __construct(Iterator $input, Outputter $outputter)
    {
        $this->input = $input;
        $this->outputter = $outputter;
    }

    public function main(Array $keys)
    {
        foreach ($this->input as $line_str) {
            $line_str = trim($line_str);
            try {
                $line = new Line($line_str);
            } catch (InvalidLineException $e) {
                $this->outputter->output($line_str);
                continue;
            }

            $this->outputter->output($line->toPlainText($keys));
        }
    }
}

<?php
namespace FlflPlainize;

use FlflPlainize\Line;
use FlflPlainize\Exception\InvalidLineException;

use Iterator;

class FlflPlainize
{
    private $input;
    private $outputer;

    public function __construct(Iterator $input, Outputer $outputer)
    {
        $this->input = $input;
        $this->outputer = $outputer;
    }

    public function main()
    {
        foreach ($this->input as $line_str) {
            try {
                $line = new Line($line_str);
            } catch(InvalidLineException $e) {
                $this->outputer->output($line_str);
                continue;
            }

            $this->outputer->output($line->toPlainText());
        }
    }
}

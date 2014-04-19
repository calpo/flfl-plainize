<?php
/**
 * This file is part of the flfl-plainize.
 */

namespace FlflPlainize;


class Outputer
{
    /**
     * @param  string  $str  string to output
     * @return void
     */
    public function output($str)
    {
        echo $str . PHP_EOL;
    }
}

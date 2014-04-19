<?php
/**
 * This file is part of the flfl-plainize.
 */

namespace FlflPlainize\Tests;


use FlflPlainize\Outputter;

class OutputterTest extends \PHPUnit_Framework_TestCase
{
    private $SUT;

    public function setUp()
    {
        $this->SUT = new Outputter();
    }

    /**
     * @test
     */
    public function itOutputsStringWithLineFeedToStdout()
    {
        ob_start();
        $this->SUT->output('foo');
        $output = ob_get_clean();
        $this->assertEquals('foo' . PHP_EOL, $output);
    }
}
 
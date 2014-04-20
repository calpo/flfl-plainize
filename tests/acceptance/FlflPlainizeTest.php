<?php
/**
 * This file is part of the flfl-plainize.
 */

namespace FlflPlainize\Tests\Acceptance;


/**
 * @coversNothing
 */
class FlflPlainizeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        exec('cd ' . ROOT);
    }

    /**
     * @test
     * @group xfail
     *        known bug. mysterious empty line was added to output.
     */
    public function itReceivesStdinAndOutputsToStdout()
    {
        exec('cat tests/fixture/normal.log | bin/flflplainize -k foo --key data.buz', $output, $retval);

        $this->assertEquals(0, $retval);
        $this->assertCount(3, $output);
    }

    /**
     * @test
     */
    public function itConvertsLogFormatToPlainText()
    {
        exec('cat tests/fixture/normal.log | bin/flflplainize -k foo --key data.buz', $output, $retval);

        list($date, $firstItem, $secondItem) = explode("\t", $output[0]);

        // comparing by timestamp, to avoid dependence on timezone setting.
        $this->assertEquals(strtotime('2014-03-18T17:25:38+0900'), strtotime($date));
        $this->assertEquals('f111', $firstItem);
        $this->assertEquals('z111', $secondItem);
    }
}

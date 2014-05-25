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
	private $fixtureDir;
	private $normalLog;
	private $cmd;

	public function setUp()
	{
		$this->fixtureDir = ROOT . '/tests/fixture';
		$this->normalLog = $this->fixtureDir . '/normal.log';
		$this->cmd = ROOT . '/bin/flflplainize';
	}

    /**
     * @test
     */
    public function itReceivesStdinAndOutputsToStdout()
    {
        exec(
			"cat {$this->normalLog} | {$this->cmd} -k foo --key data.buz",
            $output,
            $retval
        );

        $this->assertEquals(0, $retval);
        $this->assertCount(count(file($this->normalLog)) + 1, $output);
    }

    /**
     * @test
     */
    public function itConvertsLogFormatToPlainText()
    {
        exec(
			"cat {$this->normalLog} | {$this->cmd} -k foo --key data.buz",
            $output,
            $retval
        );

        list($date, $firstItem, $secondItem) = explode("\t", $output[0]);

        // comparing by timestamp, to avoid dependence on timezone setting.
        $this->assertEquals(strtotime('2014-03-18T17:25:38+0900'), strtotime($date));
        $this->assertEquals('f111', $firstItem);
        $this->assertEquals('z111', $secondItem);
    }
}

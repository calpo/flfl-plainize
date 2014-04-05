<?php
/**
 * This file is part of flfl-plainize.
 */

namespace FlflPlainize\Tests\Unit;

use FlflPlainize\Line;

class LineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itIsInitializable()
    {
        $SUT = new Line($this->sampleNormalLine());

        $this->assertInstanceOf('\FlflPlainize\Line', $SUT);
    }

    /**
     * @test
     * @expectedException \FlflPlainize\Exception\InvalidLineException
     * @dataProvider invalidLines
     */
    public function whenInvalidLineGivenItThrowsException($line, $purpose)
    {
        $SUT = new Line($line);
    }

    public function invalidLines()
    {
        return array(
            array("", 'empty.'),
            array("invalid_time\tlogname\t{}", 'invalid time format.'),
            array("2014-03-18T17:26:38+09:00\tlogname", 'insufficient column.'),
            array("2014-03-18T17:26:38+09:00\tlogname\t{in::valid}", 'invalid json.'),
        );
    }

    /**
     * @test
     */
    public function toPlainTextReturnsPlainizedJsonData()
    {
        $SUT = new Line($this->sampleNormalLine());

        $text = $SUT->toPlainText();

        $this->assertEquals(
            '2014-03-18 17:25:38' . "¥t" .
            'f111' . "¥t" .
            'b111' . "¥t" .
            'z111',
            $text
        );
    }

    private function sampleNormalLine()
    {
        return '2014-03-18T17:25:38+0900'. "\t".
            'some-log-name' . "\t" .
            '{"foo":"f111","data":{"bar":"b111","buz":"z111"}}';
    }
}

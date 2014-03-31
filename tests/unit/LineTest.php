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

        $this->assertEquals('2014-03-18T17:25:38+0900', date(DATE_ISO8601, $SUT->getTime()));
        $this->assertEquals('some-log-name', $SUT->getName());
        $this->assertEquals('2014-03-18T17:25:38+09:00', $SUT->getJson()['timestamp']);
        $this->assertEquals('b111', $SUT->getJson()['data']['bar']);
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

    private function sampleNormalLine()
    {
        return '2014-03-18T17:25:38+0900'. "\t".
            'some-log-name' . "\t" .
            '{"timestamp":"2014-03-18T17:25:38+09:00","foo":"f111","data":{"bar":"b111","buz":"z111"}}';
    }
}

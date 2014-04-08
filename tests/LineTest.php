<?php
/**
 * This file is part of flfl-plainize.
 */

namespace FlflPlainize\Tests;

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

        $this->assertEquals(
            '2014-03-18 17:25:38' . Line::TEXT_SEPARATOR .
            'f111' . Line::TEXT_SEPARATOR .
            'b111' . Line::TEXT_SEPARATOR .
            'u111' . Line::TEXT_SEPARATOR .
            'u222',
            $SUT->toPlainText()
        );
    }

    /**
     * @test
     */
    public function toPlainTextFiltersJsonArray()
    {
        $SUT = new Line($this->sampleNormalLine());

        $keys = [
            'data.buz',
            'data.buz.buz2',
        ];

        $this->assertEquals(
            "2014-03-18 17:25:38" . Line::TEXT_SEPARATOR .
            "u111" . Line::TEXT_SEPARATOR .
            "u222" . Line::TEXT_SEPARATOR .
            "u222",
            $SUT->toPlainText($keys)
        );
    }

    private function sampleNormalLine()
    {
        $json = json_encode([
            'foo'  => 'f111',
            'data' => [
                'bar' => 'b111',
                'buz' => [
                    'buz1' => 'u111',
                    'buz2' => 'u222',
                ],
            ],
        ]);
        return '2014-03-18T17:25:38+0900'. "\t".
            'some-log-name' . "\t" .
            $json;
    }
}

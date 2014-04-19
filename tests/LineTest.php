<?php
/**
 * This file is part of flfl-plainize.
 */

namespace FlflPlainize\Tests;

use FlflPlainize\Line;

class LineTest extends \PHPUnit_Framework_TestCase
{
    private $original_timezone;

    public function setUp()
    {
        $this->original_timezone = date_default_timezone_get();
        date_default_timezone_set('Asia/Tokyo');
    }

    public function tearDown()
    {
        date_default_timezone_set($this->original_timezone);
    }

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
        new Line($line);
    }

    public function invalidLines()
    {
        return array(
            array("", 'empty.'),
            array("invalid_time\tlogname\t{}", 'invalid time format.'),
            array("2014-03-18T17:26:38+09:00", 'insufficient column.'),
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
     * @dataProvider getKeyTestData
     */
    public function toPlainTextFiltersJsonArray($keys, $expect)
    {
        $SUT = new Line($this->sampleNormalLine());

        $this->assertEquals(
            "2014-03-18 17:25:38" . Line::TEXT_SEPARATOR .
            implode(Line::TEXT_SEPARATOR, $expect),
            $SUT->toPlainText($keys)
        );
    }

    public function getKeyTestData()
    {
        return [
            [
                ['foo'],
                ['f111'],
            ], [
                ['data.bar'],
                ['b111'],
            ], [
                ['data.buz'],
                ['u111', 'u222'],
            ], [
                ['data.buz', 'data.buz.buz2'],
                ['u111', 'u222', 'u222'],
            ], [
                ['invalid'],
                [],
            ], [
                ['data.invalid'],
                [],
            ]
        ];
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

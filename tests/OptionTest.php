<?php
/**
 * This file is part of the flfl-plainize.
 */

namespace FlflPlainize\Tests;


use FlflPlainize\Option;

class OptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itIsInitializable()
    {
        $this->assertInstanceOf('\FlflPlainize\Option', new Option([]));
    }

    /**
     * @test
     * @dataProvider sampleKeyOptions
     */
    public function itStoresKeyOption($opts, $expect)
    {
        $SUT = new Option($opts);

        $this->assertEquals($expect, $SUT->getKeys());
    }

    public function sampleKeyOptions()
    {
        return [
            [
                [],
                [],
            ], [
                ['k' => 'foo'],
                ['foo'],
            ], [
                ['k' => ['foo', 'bar']],
                ['foo', 'bar'],
            ], [
                ['k' => 'foo', 'key' => 'bar'],
                ['foo', 'bar'],
            ], [
                ['key' => ['foo', 'bar']],
                ['foo', 'bar'],
            ], [
                ['k' => ['foo1', 'bar1'], 'key' => ['foo2', 'bar2']],
                ['foo1', 'bar1', 'foo2', 'bar2'],
            ]
        ];
    }
}

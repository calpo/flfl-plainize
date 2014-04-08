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
     */
    public function parseArrayIntoProperty()
    {
        $SUT = new Option([]);

        $this->assertEquals([], $SUT->getKeys());
    }
}
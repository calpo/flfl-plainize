<?php
/**
 * This file is part of flfl-plainize.
 */

namespace FlflPlainize\Test\Unit;

use FlflPlainize\FlflPlainize;

class FlflPlainizeTest extends \PHPUnit_Framework_TestCase
{
    private $SUT;

    public function setUp()
    {
        $this->SUT = new FlflPlainize;
    }

    /**
     * @test
     */
    public function itIsTypeOfFlflPlainize()
    {
        $this->assertInstanceOf('\FlflPlainize\FlflPlainize', $this->SUT);
    }
}
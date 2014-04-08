<?php
/**
 * This file is part of flfl-plainize.
 */

namespace FlflPlainize\Tests;

use FlflPlainize\FlflPlainize;

class FlflPlainizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $outputer;

    public function setUp()
    {
        $this->outputer = $this->getMock('\FlflPlainize\Outputer');
    }

    /**
     * @test
     * @dataProvider getInitializeIterators
     */
    public function itIsInitializableWithIterator($input)
    {
        $SUT = new FlflPlainize($input, $this->outputer);
        $this->assertInstanceOf('\FlflPlainize\FlflPlainize', $SUT);
    }

    public function getInitializeIterators()
    {
        $file = ROOT . '/tests/fixture/normal.log';

        return array(
            array(new \ArrayIterator(array())),
            array(new \SplFileObject($file)),
        );
    }

    /**
     * @test
     */
    public function itOutputLinsByPlainText()
    {
        $file = ROOT . '/tests/fixture/normal.log';
        $SUT = new FlflPlainize(new \SplFileObject($file), $this->outputer);

        $this->outputer
            ->expects($this->atLeastOnce())
            ->method('output')
            ->withAnyParameters();

        $SUT->main();
    }
}

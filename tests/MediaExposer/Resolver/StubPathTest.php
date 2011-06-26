<?php

namespace MediaExposer\Resolver;

require_once __DIR__.'/ClassToString.php';

class StubPathTest extends \PHPUnit_Framework_TestCase
{
    public function testSupports()
    {
        $resolver = new StubPath('/the/path');

        $this->assertTrue($resolver->supports('TheMedia'));
    }

    /**
     * @dataProvider dataForGetPath
     */
    public function testGetPath($path, $expected, $message)
    {
        $resolver = new StubPath($path);

        $this->assertEquals($expected, $resolver->getPath('TheMedia'), $message);
    }

    public function dataForGetPath()
    {
        return array(
            array(
                '/the/path',
                '/the/path',
                'Should simply return the path when it is a string.'
            ),
            array(
                new ClassToString('/the/path'),
                '/the/path',
                'Should return the string representation of an object using the __toString method.'
            )
        );
    }
}

<?php

namespace MediaExposer\Resolver;

use MediaExposer\SourceResolver;

require_once __DIR__.'/ClassToString.php';

class StubSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSupports()
    {
        $resolver = new StubSource('/the/source', SourceResolver::TYPE_RELATIVE);

        $this->assertTrue($resolver->supports('TheMedia'));
    }

    /**
     * @dataProvider dataForGetSource
     */
    public function testGetSource($source, $expected, $message)
    {
        $resolver = new StubSource($source, SourceResolver::TYPE_RELATIVE);

        $this->assertEquals($expected, $resolver->getSource('TheMedia'), $message);
    }

    public function dataForGetSource()
    {
        return array(
            array(
                '/the/source',
                '/the/source',
                'Should simply return the source when it is a string.'
            ),
            array(
                new ClassToString('/the/source'),
                '/the/source',
                'Should return the string representation of an object using the __toString method.'
            )
        );
    }

    /**
     * @dataProvider dataForGetSourceType
     */
    public function testGetSourceType($source, $sourceType, $expected)
    {
        $resolver = new StubSource($source, $sourceType);

        $this->assertEquals($expected, $resolver->getSourceType('TheMedia'));
    }

    public function dataForGetSourceType()
    {
        return array(
            array(
                '/the/source',
                SourceResolver::TYPE_RELATIVE,
                SourceResolver::TYPE_RELATIVE
            ),
            array(
                'http://the.host/the/source',
                SourceResolver::TYPE_ABSOLUTE,
                SourceResolver::TYPE_ABSOLUTE
            ),
        );
    }
}

<?php

namespace Mediator;

class ExposerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetResolvers()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getSourceResolverMock(), 10);
        $exposer->addResolver($b = $this->getPathResolverMock(), -10);
        $exposer->addResolver($c = $this->getSourceResolverMock(), 0);
        $exposer->addResolver($d = $this->getPathResolverMock(), 0);

        $this->assertEquals(
            array($a, $b, $c, $d),
            iterator_to_array($exposer->getResolvers(), false),
            'Should return all the resolvers in the same order they were added.'
        );
    }

    public function testGetSortedResolvers()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getSourceResolverMock(), 10);
        $exposer->addResolver($b = $this->getPathResolverMock(), -10);
        $exposer->addResolver($c = $this->getSourceResolverMock(), 0);
        $exposer->addResolver($d = $this->getPathResolverMock(), 0);

        $this->assertEquals(
            array($b, $c, $d, $a),
            iterator_to_array($exposer->getSortedResolvers(), false),
            'Should return all the resolver sorted by priority (from the smallest to the largest).'
        );
    }

    public function testGetSourceResolvers()
    {
        $a = $this->getSourceResolverMock();
        $b = $this->getPathResolverMock();
        $c = $this->getSourceResolverMock();

        $exposer = new Exposer();
        $exposer->addResolver($a, 10);
        $exposer->addResolver($b, -10);
        $exposer->addResolver($c, 0);

        $this->assertEquals(
            array($a, $c),
            iterator_to_array($exposer->getSourceResolvers(), false),
            'Should return the sources in the order they were added.'
        );
    }

    public function testGetSortedSourceResolvers()
    {
        $a = $this->getSourceResolverMock();
        $b = $this->getPathResolverMock();
        $c = $this->getSourceResolverMock();

        $exposer = new Exposer();
        $exposer->addResolver($a, 10);
        $exposer->addResolver($b, -10);
        $exposer->addResolver($c, 0);

        $this->assertEquals(
            array($c, $a),
            iterator_to_array($exposer->getSortedSourceResolvers(), false),
            'Should return the source resolvers sorted by priority from the smallest to the largest.'
        );
    }

    public function testGetPathResolvers()
    {
        $a = $this->getPathResolverMock();
        $b = $this->getSourceResolverMock();
        $c = $this->getPathResolverMock();

        $exposer = new Exposer();
        $exposer->addResolver($a, 10);
        $exposer->addResolver($b, -10);
        $exposer->addResolver($c, 0);

        $this->assertEquals(
            array($a, $c),
            iterator_to_array($exposer->getPathResolvers(), false),
            'Should return the path resolvers in the same order they were added.'
        );
    }

    public function testGetSortedPathResolvers()
    {
        $a = $this->getPathResolverMock();
        $b = $this->getSourceResolverMock();
        $c = $this->getPathResolverMock();

        $exposer = new Exposer();
        $exposer->addResolver($a, 10);
        $exposer->addResolver($b, -10);
        $exposer->addResolver($c, 0);

        $this->assertEquals(
            array($c, $a),
            iterator_to_array($exposer->getSortedPathResolvers(), false),
            'Should return the path resolvers sorted by priority from the smallest to the largest).'
        );
    }

    private function getSourceResolverMock()
    {
        return $this->getMock('Mediator\SourceResolver');
    }

    private function getPathResolverMock()
    {
        return $this->getMock('Mediator\PathResolver');
    }
}

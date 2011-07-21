<?php

namespace MediaExposer;

class ExposerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $exposer = new Exposer();

        $this->assertAttributeEquals(null, 'baseUrl', $exposer, 'The base url should be null by default.');

        $exposer = new Exposer('http://the-base.url');

        $this->assertAttributeEquals('http://the-base.url', 'baseUrl', $exposer, 'The base url should have the value providen in the constructor.');
    }

    public function testSetBaseUrl()
    {
        $exposer = new Exposer('http://the-old-base.url');
        $exposer->setBaseUrl('http://the-new-base.url');

        $this->assertAttributeEquals('http://the-new-base.url', 'baseUrl', $exposer, 'The base url should have the new defined value.');
    }

    public function testGetPath()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getPathResolverMock());
        $exposer->addResolver($b = $this->getPathResolverMock());
        $exposer->addResolver($c = $this->getPathResolverMock());

        $a
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(false))
        ;
        $a
            ->expects($this->never())
            ->method('getPath')
        ;

        $b
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(true))
        ;
        $b
            ->expects($this->once())
            ->method('getPath')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue('/path/of/the/media'))
        ;

        $c
            ->expects($this->never())
            ->method('supports')
        ;

        $this->assertEquals(
            '/path/of/the/media',
            $exposer->getPath('TheMedia', array('foo' => 'bar')),
            'Should return the value providen by the second resolver.'
        );
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetPathWhenThereIsNotSupportingResolver()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getPathResolverMock());
        $exposer->addResolver($b = $this->getPathResolverMock());

        $a
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(false))
        ;

        $b
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(false))
        ;

        $exposer->getPath('TheMedia', array('foo' => 'bar'));
    }

    public function testGetSource()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getSourceResolverMock());
        $exposer->addResolver($b = $this->getSourceResolverMock());
        $exposer->addResolver($c = $this->getSourceResolverMock());

        $a
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(false))
        ;
        $a
            ->expects($this->never())
            ->method('getSource')
        ;

        $b
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(true))
        ;
        $b
            ->expects($this->once())
            ->method('getSource')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue('/source/of/the/media'))
        ;

        $c
            ->expects($this->never())
            ->method('supports')
        ;

        $this->assertEquals(
            '/source/of/the/media',
            $exposer->getSource('TheMedia', array('foo' => 'bar')),
            'Should return the value providen by the second resolver.'
        );
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetSourceWithNoSupportingResolver()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getSourceResolverMock());
        $exposer->addResolver($b = $this->getSourceResolverMock());

        $a
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(false))
        ;

        $b
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo('TheMedia'), $this->equalTo(array('foo' => 'bar')))
            ->will($this->returnValue(false))
        ;

        $exposer->getSource('TheMedia', array('foo' => 'bar'));
    }

    public function testGetSourceWithForceAbsoluteAndAnAbsoluteResolver()
    {
        $exposer = new Exposer('http://the-host');
        $exposer->addResolver($a = $this->getSourceResolverMock());

        $a
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true))
        ;
        $a
            ->expects($this->once())
            ->method('getSource')
            ->will($this->returnValue('http://the-absolute/source'))
        ;
        $a
            ->expects($this->once())
            ->method('getSourceType')
            ->will($this->returnValue(SourceResolver::TYPE_ABSOLUTE))
        ;

        $this->assertEquals(
            'http://the-absolute/source',
            $exposer->getSource('foo', array(), true),
            'Should NOT prepend the base url to the absolute resolver\'s source when $forceAbsolute is set to TRUE.'
        );
    }

    public function testGetSourceWithForceAbsoluteAndAnRelativeResolver()
    {
        $exposer = new Exposer('http://the-host');
        $exposer->addResolver($a = $this->getSourceResolverMock());

        $a
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true))
        ;
        $a
            ->expects($this->once())
            ->method('getSource')
            ->will($this->returnValue('/the/relative/source'))
        ;
        $a
            ->expects($this->once())
            ->method('getSourceType')
            ->will($this->returnValue(SourceResolver::TYPE_RELATIVE))
        ;

        $this->assertEquals(
            'http://the-host/the/relative/source',
            $exposer->getSource('foo', array(), true),
            'Should prepend the base url to the relative resolver\'s source when $forceAbsolute is set to TRUE.'
        );
    }

    public function testGetResolvers()
    {
        $exposer = new Exposer();
        $exposer->addResolver($a = $this->getSourceResolverMock(), -10);
        $exposer->addResolver($b = $this->getPathResolverMock(), 10);
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
        $exposer->addResolver($a = $this->getSourceResolverMock(), -10);
        $exposer->addResolver($b = $this->getPathResolverMock(), 10);
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
        $exposer->addResolver($a, -10);
        $exposer->addResolver($b, 10);
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
        $exposer->addResolver($a, -10);
        $exposer->addResolver($b, 10);
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
        $exposer->addResolver($a, -10);
        $exposer->addResolver($b, 10);
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
        $exposer->addResolver($a, -10);
        $exposer->addResolver($b, 10);
        $exposer->addResolver($c, 0);

        $this->assertEquals(
            array($c, $a),
            iterator_to_array($exposer->getSortedPathResolvers(), false),
            'Should return the path resolvers sorted by priority from the smallest to the largest).'
        );
    }

    public function testAbsolutify()
    {
        $exposer = new Exposer('http://the-host');

        $r = new \ReflectionMethod($exposer, 'absolutify');
        $r->setAccessible(true);

        $this->assertEquals(
            'http://the-host/the/relative/source',
            $r->invokeArgs($exposer, array('/the/relative/source')),
            'Should prepend the base url to the given relative source.'
        );
    }

    /**
     * @expectedException LogicException
     */
    public function testAbsolutifyWhenTheBaseUrlIsNotDefined()
    {
        $exposer = new Exposer();

        $r = new \ReflectionMethod($exposer, 'absolutify');
        $r->setAccessible(true);

        $r->invokeArgs($exposer, array('/the/relative/source'));
    }

    private function getSourceResolverMock()
    {
        return $this->getMock('MediaExposer\SourceResolver');
    }

    private function getPathResolverMock()
    {
        return $this->getMock('MediaExposer\PathResolver');
    }
}

<?php

namespace Mediator\Resolver;

require_once __DIR__.'/ClassCallback.php';

class CallbackSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsWithAClosure()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue(true))
        ;

        $resolver = new CallbackSource(
            function() use($callback) {
                return call_user_func_array(
                    array($callback, 'callback'),
                    func_get_args()
                );
            },
            function() {},
            function() {}
        );

        $this->assertTrue($resolver->supports('TheMedia', array('foo' => 'bar')));
    }

    public function testSupportsWithACallbackArray()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue(true))
        ;

        $resolver = new CallbackSource(
            array($callback, 'callback'),
            function() {},
            function() {}
        );

        $this->assertTrue($resolver->supports('TheMedia', array('foo' => 'bar')));
    }

    public function testGetSourceWithAClosure()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('TheSource'))
        ;

        $resolver = new CallbackSource(
            function() {},
            function() use($callback) {
                return call_user_func_array(
                    array($callback, 'callback'),
                    func_get_args()
                );
            },
            function() {}
        );

        $this->assertEquals('TheSource', $resolver->getSource('TheMedia', array('foo' => 'bar')));
    }

    public function testGetSourceWithACallback()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('TheSource'))
        ;

        $resolver = new CallbackSource(
            function() {},
            array($callback, 'callback'),
            function() {}
        );

        $this->assertEquals('TheSource', $resolver->getSource('TheMedia', array('foo' => 'bar')));
    }

    public function testGetSourceTypeWithAClosure()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('TheSourceType'))
        ;

        $resolver = new CallbackSource(
            function() {},
            function() {},
            function() use($callback) {
                return call_user_func_array(
                    array($callback, 'callback'),
                    func_get_args()
                );
            }
        );

        $this->assertEquals('TheSourceType', $resolver->getSourceType('TheMedia', array('foo' => 'bar')));
    }

    public function testGetSourceTypeWithACallback()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('TheSourceType'))
        ;

        $resolver = new CallbackSource(
            function() {},
            function() {},
            array($callback, 'callback')
        );

        $this->assertEquals('TheSourceType', $resolver->getSourceType('TheMedia', array('foo' => 'bar')));
    }

    private function getClassCallbackMock()
    {
        return $this->getMock('Mediator\Resolver\ClassCallback');
    }
}

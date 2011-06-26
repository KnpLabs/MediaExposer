<?php

namespace Mediator\Resolver;

require_once __DIR__.'/ClassCallback.php';

class CallbackPathTest extends \PHPUnit_Framework_TestCase
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

        $resolver = new CallbackPath(
            function() use($callback) {
                return call_user_func_array(
                    array($callback, 'callback'),
                    func_get_args()
                );
            },
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

        $resolver = new CallbackPath(
            array($callback, 'callback'),
            function() {}
        );

        $this->assertTrue($resolver->supports('TheMedia', array('foo' => 'bar')));
    }

    public function testGetPathWithAClosure()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('ThePath'))
        ;

        $resolver = new CallbackPath(
            function() {},
            function() use($callback) {
                return call_user_func_array(
                    array($callback, 'callback'),
                    func_get_args()
                );
            }
        );

        $this->assertEquals('ThePath', $resolver->getPath('TheMedia', array('foo' => 'bar')));
    }

    public function testGetPathWithACallback()
    {
        $callback = $this->getClassCallbackMock();
        $callback
            ->expects($this->once())
            ->method('callback')
            ->with(
                $this->equalTo('TheMedia'),
                $this->equalTo(array('foo' => 'bar'))
            )
            ->will($this->returnValue('ThePath'))
        ;

        $resolver = new CallbackPath(
            function() {},
            array($callback, 'callback')
        );

        $this->assertEquals('ThePath', $resolver->getPath('TheMedia', array('foo' => 'bar')));
    }

    private function getClassCallbackMock()
    {
        return $this->getMock('Mediator\Resolver\ClassCallback');
    }
}

<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zenstruck\ControllerUtil\EventListener\ForwardListener;
use Zenstruck\ControllerUtil\Forward;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ForwardListenerTest extends BaseListenerTest
{
    public function testCreatesForwardResponse()
    {
        $listener = new ForwardListener();
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernel', array(), array(), '',false);
        $kernel
            ->expects($this->once())
            ->method('handle')
            ->will($this->returnValue(new Response('foobar')))
        ;
        $event = new GetResponseForControllerResultEvent(
            $kernel,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            new Forward('my_controller')
        );
        $listener->onKernelView($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $event->getResponse());
        $this->assertSame('foobar', $event->getResponse()->getContent());
    }

    public function testSkip()
    {
        $listener = new ForwardListener();
        $event = $this->createEvent('foo');
        $listener->onKernelView($event);

        $this->assertNull($event->getResponse());
    }
}

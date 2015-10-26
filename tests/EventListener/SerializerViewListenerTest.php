<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\SerializerViewListener;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SerializerViewListenerTest extends ViewListenerTest
{
    public function testCreatesResponse()
    {
        $object = new View('foo');

        $serializer = $this->createSerializer();
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($object->getData())
            ->will($this->returnValue('"foo"'))
        ;

        $listener = new SerializerViewListener($serializer);
        $event = $this->createEvent($object, 'json');
        $listener->onKernelView($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $event->getResponse());
        $this->assertSame('"foo"', $event->getResponse()->getContent());
    }

    public function testSkipFormat()
    {
        $listener = $this->createListener();
        $event = $this->createEvent(new View('foo'));
        $listener->onKernelView($event);

        $this->assertNull($event->getResponse());
    }

    protected function createListener()
    {
        return new SerializerViewListener($this->createSerializer());
    }

    private function createSerializer()
    {
        return $this->getMock('JMS\Serializer\SerializerInterface');
    }
}

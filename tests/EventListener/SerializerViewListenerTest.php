<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\SerializerViewListener;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SerializerViewListenerTest extends ViewListenerTest
{
    /**
     * @dataProvider serializerProvider
     */
    public function testCreatesResponse($serializer)
    {
        $object = new View('foo');

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

    /**
     * @dataProvider serializerProvider
     */
    public function testSkipFormat($serializer)
    {
        $listener = new SerializerViewListener($serializer);
        $event = $this->createEvent(new View('foo'));
        $listener->onKernelView($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSerializer()
    {
        $listener = new SerializerViewListener('foo');
    }

    public function serializerProvider()
    {
        return array(
            array($this->getMock('JMS\Serializer\SerializerInterface')),
            array($this->getMock('Symfony\Component\Serializer\SerializerInterface')),
        );
    }

    protected function createListener()
    {
        return new SerializerViewListener($this->getMock('JMS\Serializer\SerializerInterface'));
    }
}

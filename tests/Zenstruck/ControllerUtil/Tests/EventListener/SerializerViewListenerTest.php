<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use JMS\Serializer\SerializerBuilder;
use Zenstruck\ControllerUtil\EventListener\SerializerViewListener;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SerializerViewListenerTest extends ViewListenerTest
{
    public function testCreatesResponse()
    {
        $listener = $this->createListener();
        $event = $this->createEvent(new View('foo'), 'json');
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
        return new SerializerViewListener(SerializerBuilder::create()->build());
    }
}

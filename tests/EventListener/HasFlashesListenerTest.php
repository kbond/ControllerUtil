<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Zenstruck\ControllerUtil\EventListener\HasFlashesListener;
use Zenstruck\ControllerUtil\FlashRedirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class HasFlashesListenerTest extends BaseListenerTest
{
    public function testAddsFlashes()
    {
        $flashBag = new FlashBag();
        $listener = new HasFlashesListener($flashBag);
        $event = $this->createEvent(FlashRedirect::createSimple('foo', 'bar'));
        $listener->onKernelView($event);

        $this->assertSame(array('info' => array('bar')), $flashBag->all());
        $this->assertNull($event->getResponse());
    }

    public function testSkip()
    {
        $flashBag = new FlashBag();
        $listener = new HasFlashesListener($flashBag);
        $event = $this->createEvent('foo');
        $listener->onKernelView($event);

        $this->assertEmpty($flashBag->all());
        $this->assertNull($event->getResponse());
    }
}

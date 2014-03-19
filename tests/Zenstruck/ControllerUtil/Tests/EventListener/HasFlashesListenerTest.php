<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Zenstruck\ControllerUtil\EventListener\HasFlashesListener;
use Zenstruck\ControllerUtil\FlashRedirect;
use Zenstruck\ControllerUtil\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class HasFlashesListenerTest extends BaseListenerTest
{
    public function testHasFlashesResult()
    {
        $flashBag = new FlashBag();
        $listener = new HasFlashesListener($flashBag);
        $event = $this->createEvent(FlashRedirect::createSimple('foo', 'bar'));
        $listener->onKernelView($event);

        $this->assertSame(array('info' => array('bar')), $flashBag->all());
        $this->assertNull($event->getResponse());
    }

    public function testNonHasFlashesResult()
    {
        $flashBag = new FlashBag();
        $listener = new HasFlashesListener($flashBag);
        $event = $this->createEvent(new Redirect('foo'));
        $listener->onKernelView($event);

        $this->assertEmpty($flashBag->all());
        $this->assertNull($event->getResponse());
    }
}

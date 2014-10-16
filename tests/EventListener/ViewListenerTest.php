<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\ViewListener;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ViewListenerTest extends BaseListenerTest
{
    public function testSkipResult()
    {
        $listener = $this->createListener();
        $event = $this->createEvent('foo');
        $listener->onKernelView($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * @return ViewListener
     */
    abstract protected function createListener();
}

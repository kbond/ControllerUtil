<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\RedirectListener;
use Zenstruck\ControllerUtil\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RedirectListenerTest extends BaseListenerTest
{
    public function testCreatesRedirectResponse()
    {
        $urlGenerator = $this->getUrlGenerator();
        $urlGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('http://www.foobar.com'))
        ;

        $listener = new RedirectListener($urlGenerator);
        $event = $this->createEvent(new Redirect('foo', array(), 301));
        $listener->onKernelView($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $event->getResponse());
        $this->assertSame('http://www.foobar.com', $event->getResponse()->getTargetUrl());
        $this->assertSame(301, $event->getResponse()->getStatusCode());
    }

    public function testSkip()
    {
        $urlGenerator = $this->getUrlGenerator();
        $listener = new RedirectListener($urlGenerator);
        $event = $this->createEvent('foo');
        $listener->onKernelView($event);

        $this->assertNull($event->getResponse());
    }

    private function getUrlGenerator()
    {
        return $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate'),
            array(),
            '',
            false
        );
    }
}

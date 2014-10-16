<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\TwigViewListener;
use Zenstruck\ControllerUtil\Template;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TwigViewListenerTest extends ViewListenerTest
{
    public function testCreatesResponse()
    {
        $object = new Template('foobar', array('foo' => 'bar'));

        $template = $this->getMock('Twig_Template', array('render'));
        $template->expects($this->once())
            ->method('render')
            ->with($object->getDataAsArray())
            ->will($this->returnValue('this is the rendered foobar template'))
        ;

        $twig = $this->getMock('Twig_Environment', array('resolveTemplate'));
        $twig->expects($this->once())
            ->method('resolveTemplate')
            ->with($object->getTemplate())
            ->will($this->returnValue($template))
        ;

        $listener = new TwigViewListener($twig);
        $event = $this->createEvent($object);
        $listener->onKernelView($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $event->getResponse());
        $this->assertSame('this is the rendered foobar template', $event->getResponse()->getContent());
    }

    protected function createListener()
    {
        return new TwigViewListener($this->createTwigEnvironment());
    }

    private function createTwigEnvironment()
    {
        return $this->getMock('Twig_Environment');
    }
}

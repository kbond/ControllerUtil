<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\TemplatingViewListener;
use Zenstruck\ControllerUtil\Template;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TemplatingViewListenerTest extends ViewListenerTest
{
    public function testCreatesResponse()
    {
        $object = new Template('foobar', array('foo' => 'bar'));

        $engine = $this->createEngine();
        $engine->expects($this->once())
            ->method('render')
            ->with($object->getTemplate(), $object->getDataAsArray())
            ->will($this->returnValue('this is the rendered foobar template'))
        ;

        $listener = new TemplatingViewListener($engine);
        $event = $this->createEvent($object);
        $listener->onKernelView($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $event->getResponse());
        $this->assertSame('this is the rendered foobar template', $event->getResponse()->getContent());
    }

    public function testSkipsWhenNoTemplate()
    {
        $engine = $this->createEngine();
        $listener = new TemplatingViewListener($engine);
        $event = $this->createEvent(new View('foo'));
        $this->assertNull($event->getResponse());

        $listener->onKernelView($event);
        $this->assertNull($event->getResponse());
    }

    public function testCreatesResponseWithTemplateArray()
    {
        $object = new Template(array('foobar', 'foobaz'), array('foo' => 'bar'));

        $engine = $this->createEngine();
        $engine->expects($this->once())
            ->method('exists')
            ->with('foobar')
            ->will($this->returnValue(true))
        ;

        $engine->expects($this->once())
            ->method('render')
            ->with('foobar', $object->getDataAsArray())
            ->will($this->returnValue('this is the rendered foobar template'))
        ;

        $listener = new TemplatingViewListener($engine);
        $event = $this->createEvent($object);
        $listener->onKernelView($event);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $event->getResponse());
        $this->assertSame('this is the rendered foobar template', $event->getResponse()->getContent());
    }

    protected function createListener()
    {
        return new TemplatingViewListener($this->createEngine());
    }

    private function createEngine()
    {
        return $this->getMock('Symfony\Component\Templating\EngineInterface');
    }
}

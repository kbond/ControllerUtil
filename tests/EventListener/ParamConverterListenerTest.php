<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zenstruck\ControllerUtil\EventListener\ParamConverterListener;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ParamConverterListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider controllerProvider
     */
    public function testOnKernelControllerWithSupport($controller)
    {
        $fixture = new ParamConverterListenerFixture();
        $request = new Request();
        $event = $this->createEvent($controller, $request);

        $listener = new ParamConverterListener();
        $listener->onKernelController($event);

        $this->assertNull($request->attributes->get('fixture'));

        $converter = $this->getMock('Zenstruck\ControllerUtil\ParamConverter\ParamConverter');
        $converter
            ->expects($this->once())
            ->method('supports')
            ->with(get_class($fixture))
            ->will($this->returnValue(true))
        ;
        $converter
            ->expects($this->once())
            ->method('getObject')
            ->with($request)
            ->will($this->returnValue($fixture))
        ;

        $listener->addParamConverter($converter);
        $listener->onKernelController($event);

        $this->assertSame($fixture, $request->attributes->get('fixture'));
    }

    /**
     * @dataProvider controllerProvider
     */
    public function testOnKernelControllerWithoutSupport($controller)
    {
        $fixture = new ParamConverterListenerFixture();
        $request = new Request();
        $event = $this->createEvent($controller, $request);

        $converter = $this->getMock('Zenstruck\ControllerUtil\ParamConverter\ParamConverter');
        $converter
            ->expects($this->once())
            ->method('supports')
            ->with(get_class($fixture))
            ->will($this->returnValue(false))
        ;

        $listener = new ParamConverterListener();
        $listener->addParamConverter($converter);
        $listener->onKernelController($event);

        $this->assertNull($request->attributes->get('fixture'));
    }

    public function controllerProvider()
    {
        return array(
            array(function ($id, ParamConverterListenerFixture $fixture) { }),
            array(array('Zenstruck\ControllerUtil\Tests\EventListener\ParamConverterListenerController', 'fooAction')),
        );
    }

    private function createEvent($controller, Request $request)
    {
        return new FilterControllerEvent(
            $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            $controller,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }
}

class ParamConverterListenerController
{
    public function fooAction($id, ParamConverterListenerFixture $fixture)
    {
    }
}

class ParamConverterListenerFixture
{
}

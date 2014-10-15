<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zenstruck\ControllerUtil\EventListener\ConvertExceptionListener;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ConvertExceptionListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnKernelExceptionNoMap()
    {
        $listener = new ConvertExceptionListener();
        $exception = new \RuntimeException('foo');
        $event = $this->createEvent($exception);

        $listener->onKernelException($event);

        $this->assertSame($exception, $event->getException());
    }

    public function testOnKernelExceptionWithMap()
    {
        $listener = new ConvertExceptionListener(array('RuntimeException' => 500, 'InvalidArgumentException' => 404));

        $event = $this->createEvent(new \RuntimeException('foo'));
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\HttpException', $exception);
        $this->assertSame(500, $exception->getStatusCode());
        $this->assertSame('', $exception->getMessage());
        $this->assertInstanceOf('RuntimeException', $exception->getPrevious());
        $this->assertSame('foo', $exception->getPrevious()->getMessage());

        $event = $this->createEvent(new \InvalidArgumentException('bar'));
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\HttpException', $exception);
        $this->assertSame(404, $exception->getStatusCode());
        $this->assertSame('', $exception->getMessage());

        $event = $this->createEvent(new \LogicException('bar'));
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('LogicException', $exception);
        $this->assertSame('bar', $exception->getMessage());

        $event = $this->createEvent(new NotFoundHttpException('foo'));
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', $exception);
        $this->assertSame(404, $exception->getStatusCode());
        $this->assertSame('foo', $exception->getMessage());
    }

    private function createEvent(\Exception $exception)
    {
        return new GetResponseForExceptionEvent(
            $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $exception
        );
    }
}

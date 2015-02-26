<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zenstruck\ControllerUtil\EventListener\ConvertExceptionListener;
use Zenstruck\ControllerUtil\Exception\HasSafeMessage;
use Zenstruck\ControllerUtil\Exception\SafeMessage;

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
        $this->assertEquals('', $exception->getMessage());
        $this->assertInstanceOf('RuntimeException', $exception->getPrevious());
        $this->assertSame('foo', $exception->getPrevious()->getMessage());

        $event = $this->createEvent(new \InvalidArgumentException('bar'));
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\HttpException', $exception);
        $this->assertSame(404, $exception->getStatusCode());
        $this->assertEquals('', $exception->getMessage());

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

    public function testOnKernelExceptionWithSafeMessages()
    {
        $listener = new ConvertExceptionListener(array(
            'Zenstruck\ControllerUtil\Tests\EventListener\SafeMessageException' => 500,
            'Zenstruck\ControllerUtil\Tests\EventListener\HasSafeMessageException' => 404,
        ));

        $exception = new SafeMessageException('safe message');
        $event = $this->createEvent($exception);
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\HttpException', $exception);
        $this->assertSame(500, $exception->getStatusCode());
        $this->assertSame('safe message', $exception->getMessage());

        $exception = new HasSafeMessageException('unsafe message');
        $event = $this->createEvent($exception);
        $listener->onKernelException($event);
        $exception = $event->getException();

        $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\HttpException', $exception);
        $this->assertSame(404, $exception->getStatusCode());
        $this->assertSame('this is a safe message', $exception->getMessage());
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

class SafeMessageException extends \Exception implements SafeMessage
{
}

class HasSafeMessageException extends \Exception implements HasSafeMessage
{
    public function getSafeMessage()
    {
        return 'this is a safe message';
    }
}

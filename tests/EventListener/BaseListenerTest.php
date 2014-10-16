<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class BaseListenerTest extends \PHPUnit_Framework_TestCase
{
    protected function createEvent($result, $format = 'html')
    {
        return new GetResponseForControllerResultEvent(
            $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            new Request(array(), array(), array('_format' => $format)),
            HttpKernelInterface::MASTER_REQUEST,
            $result
        );
    }
}

<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Zenstruck\ControllerUtil\Forward;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ForwardListener
{
    private $httpKernel;

    public function __construct(HttpKernelInterface $httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result instanceof Forward) {
            return;
        }

        $parameters = $result->getParameters();
        $parameters['_controller'] = $result->getController();
        $subRequest = $event->getRequest()->duplicate(null, null, $parameters);
        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        $event->setResponse($response);
    }
}

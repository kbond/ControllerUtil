<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zenstruck\ControllerUtil\Forward;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ForwardListener
{
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result instanceof Forward) {
            return;
        }

        $parameters = $result->getParameters();
        $parameters['_controller'] = $result->getController();
        $subRequest = $event->getRequest()->duplicate(null, null, $parameters);
        $response = $event->getKernel()->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        $event->setResponse($response);
    }
}

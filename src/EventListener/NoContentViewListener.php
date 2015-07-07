<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NoContentViewListener
{
    private $allowNull;

    /**
     * @param bool $allowNull
     */
    public function __construct($allowNull = true)
    {
        $this->allowNull = $allowNull;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (null === $result && true === $this->allowNull) {
            $event->setResponse($this->createResponse());

            return;
        }

        if (!$result instanceof View) {
            return;
        }

        if (null === $result->getData() && null === $result->getTemplate()) {
            $event->setResponse($this->createResponse($result->getHeaders()));
        }
    }

    /**
     * @param array $headers
     *
     * @return Response
     */
    private function createResponse(array $headers = array())
    {
        return new Response('', 204, $headers);
    }
}

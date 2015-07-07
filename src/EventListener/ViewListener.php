<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ViewListener
{
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $format = $event->getRequest()->attributes->get('_format', 'html');

        if (!$result instanceof View) {
            return;
        }

        if (!$this->supports($result, $format)) {
            return;
        }

        $response = Response::create(
            $this->getContent($result, $format),
            $result->getStatusCode(),
            $result->getHeaders()
        )
        ->setCache($result->getCache())
        ;

        $event->setResponse($response);
    }

    /**
     * @param View   $view
     * @param string $format
     *
     * @return bool
     */
    abstract protected function supports(View $view, $format);

    /**
     * @param View   $view
     * @param string $format
     *
     * @return string
     */
    abstract protected function getContent(View $view, $format);
}

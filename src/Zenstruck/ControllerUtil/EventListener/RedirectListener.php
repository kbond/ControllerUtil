<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Zenstruck\ControllerUtil\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RedirectListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result instanceof Redirect) {
            return;
        }

        $event->setResponse(new RedirectResponse(
                $this->urlGenerator->generate($result->getRoute(), $result->getParameters()),
                $result->getStatusCode()
            )
        );
    }
}

<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Zenstruck\ControllerUtil\HasFlashes;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class HasFlashesListener
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result instanceof HasFlashes) {
            return;
        }

        foreach ($result->getFlashes() as $type => $messages) {
            foreach ($messages as $message) {
                $this->flashBag->add($type, $message);
            }
        }
    }
}

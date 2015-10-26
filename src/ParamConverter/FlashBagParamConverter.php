<?php

namespace Zenstruck\ControllerUtil\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FlashBagParamConverter implements ParamConverter
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject(Request $request)
    {
        return $this->flashBag;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($class)
    {
        return is_subclass_of($class, 'Symfony\\Component\\HttpFoundation\\Session\\Flash\\FlashBagInterface') ||
            $class === 'Symfony\\Component\\HttpFoundation\\Session\\Flash\\FlashBagInterface';
    }
}

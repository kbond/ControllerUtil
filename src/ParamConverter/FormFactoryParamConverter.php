<?php

namespace Zenstruck\ControllerUtil\ParamConverter;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FormFactoryParamConverter implements ParamConverter
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject(Request $request)
    {
        return $this->formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($class)
    {
        return is_subclass_of($class, 'Symfony\\Component\\Form\\FormFactoryInterface') ||
            $class === 'Symfony\\Component\\Form\\FormFactoryInterface';
    }
}

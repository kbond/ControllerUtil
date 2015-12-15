<?php

namespace Zenstruck\ControllerUtil\EventListener;

use JMS\Serializer\SerializerInterface as JMSSerializer;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SerializerViewListener extends ViewListener
{
    private $serializer;

    /**
     * @param JMSSerializer|SymfonySerializer $serializer
     */
    public function __construct($serializer)
    {
        if (!$serializer instanceof SymfonySerializer && !$serializer instanceof JMSSerializer) {
            throw new \InvalidArgumentException('Serializer must be instance of Symfony\Component\Serializer\Serializer or JMS\Serializer\SerializerInterface.');
        }

        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports(View $view, $format)
    {
        return 'html' !== $format;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContent(View $view, $format)
    {
        return $this->serializer->serialize($view->getData(), $format);
    }
}

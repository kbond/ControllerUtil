<?php

namespace Zenstruck\ControllerUtil\EventListener;

use JMS\Serializer\SerializerInterface;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SerializerViewListener extends ViewListener
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsFormat($format)
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

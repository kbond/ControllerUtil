<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TwigViewListener extends ViewListener
{
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(View $view, $format)
    {
        $template = $this->twig->resolveTemplate($view->getTemplate());

        return $template->render($view->getDataAsArray());
    }
}

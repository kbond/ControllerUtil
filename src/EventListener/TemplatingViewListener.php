<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\Templating\EngineInterface;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TemplatingViewListener extends ViewListener
{
    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContent(View $view, $format)
    {
        $template = $view->getTemplate();

        if (is_array($template)) {
            foreach ($template as $t) {
                if ($this->templating->exists($t)) {
                    $template = $t;
                    break;
                }
            }
        }

        return $this->templating->render($template, $view->getDataAsArray());
    }
}

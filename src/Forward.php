<?php

namespace Zenstruck\ControllerUtil;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Forward
{
    private $controller;
    private $parameters;

    /**
     * @param string $controller
     * @param array  $parameters
     */
    public function __construct($controller, array $parameters = array())
    {
        $this->controller = $controller;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}

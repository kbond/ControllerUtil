<?php

namespace Zenstruck\ControllerUtil;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Redirect
{
    const DEFAULT_STATUS_CODE = 302;

    private $route;
    private $parameters;
    private $statusCode;

    /**
     * @param string $route
     * @param array  $parameters
     * @param int    $statusCode
     */
    public function __construct($route, array $parameters = array(), $statusCode = self::DEFAULT_STATUS_CODE)
    {
        $this->route = $route;
        $this->parameters = $parameters;
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}

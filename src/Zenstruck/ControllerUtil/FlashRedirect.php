<?php

namespace Zenstruck\ControllerUtil;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FlashRedirect extends Redirect implements HasFlashes
{
    private $flashes;

    /**
     * @param string $route
     * @param array  $parameters
     * @param string $message
     * @param string $type
     *
     * @return static
     */
    public static function create($route, array $parameters, $message, $type = 'info')
    {
        return new static($route, $parameters, array($type => array($message)));
    }

    /**
     * @param string $route
     * @param string $message
     * @param string $type
     *
     * @return static
     */
    public static function createSimple($route, $message, $type = 'info')
    {
        return new static($route, array(), array($type => array($message)));
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param array  $flashes
     * @param int    $status
     */
    public function __construct($route, array $parameters = array(), array $flashes = array(), $status = 302)
    {
        $this->flashes = $flashes;

        parent::__construct($route, $parameters, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getFlashes()
    {
        return $this->flashes;
    }
}

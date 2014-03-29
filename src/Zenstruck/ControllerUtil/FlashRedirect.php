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
     * @param int    $statusCode
     *
     * @return static
     */
    public static function create($route, array $parameters, $message, $type = 'info', $statusCode = self::DEFAULT_STATUS_CODE)
    {
        return new static($route, $parameters, array($type => array($message)), $statusCode);
    }

    /**
     * @param string $route
     * @param string $message
     * @param string $type
     * @param int    $statusCode
     *
     * @return static
     */
    public static function createSimple($route, $message, $type = 'info', $statusCode = self::DEFAULT_STATUS_CODE)
    {
        return new static($route, array(), array($type => array($message)), $statusCode);
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param array  $flashes
     * @param int    $statusCode
     */
    public function __construct($route, array $parameters = array(), array $flashes = array(), $statusCode = self::DEFAULT_STATUS_CODE)
    {
        $this->flashes = $flashes;

        parent::__construct($route, $parameters, $statusCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getFlashes()
    {
        return $this->flashes;
    }
}

<?php

namespace Zenstruck\ControllerUtil;

/**
 * Helper class to ease creation of template views.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Template extends View
{
    /**
     * @param string|array $template
     * @param int          $sharedMaxAge
     * @param array        $parameters
     * @param int          $statusCode
     *
     * @return static
     */
    public static function createCached($template, $sharedMaxAge, $parameters = array(), $statusCode = self::DEFAULT_STATUS_CODE)
    {
        return new static($template, $parameters, $statusCode, array('s_maxage' => $sharedMaxAge));
    }

    /**
     * @param string|array $template
     * @param mixed        $parameters
     * @param int          $statusCode
     * @param array        $cache
     * @param array        $headers
     */
    public function __construct(
        $template,
        $parameters = array(),
        $statusCode = self::DEFAULT_STATUS_CODE,
        array $cache = array(),
        array $headers = array()
    ) {
        parent::__construct($parameters, $statusCode, $template, $cache, $headers);
    }
}

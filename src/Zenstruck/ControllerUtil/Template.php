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
     *
     * @return static
     */
    public static function createCached($template, $sharedMaxAge, $parameters = array())
    {
        return new static($template, $parameters, 200, array('s_maxage' => $sharedMaxAge));
    }

    /**
     * @param string|array $template
     * @param mixed        $parameters
     * @param int          $statusCode
     * @param array        $cache
     * @param array        $headers
     */
    public function __construct($template, $parameters = array(), $statusCode = 200, array $cache = array(), array $headers = array())
    {
        parent::__construct($parameters, $statusCode, $template, $cache, $headers);
    }
}

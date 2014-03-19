<?php

namespace Zenstruck\ControllerUtil;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class View
{
    private $data;
    private $statusCode;
    private $template;
    private $cache;
    private $headers;

    /**
     * @param mixed $data
     * @param int   $sharedMaxAge
     *
     * @return static
     */
    public static function createCached($data, $sharedMaxAge)
    {
        return new static($data, 200, null, array('s_maxage' => $sharedMaxAge));
    }

    /**
     * @param mixed        $data
     * @param int          $statusCode
     * @param string|array $template
     * @param array        $cache
     * @param array        $headers
     */
    public function __construct($data, $statusCode = 200, $template = null, array $cache = array(), array $headers = array())
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->template = $template;
        $this->cache = $cache;
        $this->headers = $headers;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $key The key to use when creating array if data isn't already an array
     *
     * @return array
     */
    public function getDataAsArray($key = 'data')
    {
        $data = $this->getData();

        if (null === $data) {
            return array();
        }

        if (!is_array($data)) {
            $data = array($key => $data);
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Either the template name or an array of templates.
     *
     * @return string|array|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Returns an array of cache options.
     *
     * @see Symfony\Component\HttpFoundation\Response::setCache
     *
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}

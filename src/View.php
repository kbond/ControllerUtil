<?php

namespace Zenstruck\ControllerUtil;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class View implements \ArrayAccess
{
    const DEFAULT_STATUS_CODE = 200;

    private $data;
    private $statusCode;
    private $template;
    private $cache;
    private $headers;

    /**
     * @param mixed $data
     * @param int   $sharedMaxAge
     * @param int   $statusCode
     *
     * @return static
     */
    public static function createCached($data, $sharedMaxAge, $statusCode = self::DEFAULT_STATUS_CODE)
    {
        return new static($data, $statusCode, null, array('s_maxage' => $sharedMaxAge));
    }

    /**
     * @param mixed        $data
     * @param int          $statusCode
     * @param string|array $template
     * @param array        $cache
     * @param array        $headers
     */
    public function __construct(
        $data,
        $statusCode = self::DEFAULT_STATUS_CODE,
        $template = null,
        array $cache = array(),
        array $headers = array()
    )
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

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $this->ensureDataIsArray();

        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $this->ensureDataIsArray();

        return $this->data[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->ensureDataIsArray();

        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->ensureDataIsArray();

        unset($this->data[$offset]);
    }

    private function ensureDataIsArray()
    {
        if (is_array($this->data)) {
            return;
        }

        $this->data = $this->getDataAsArray();
    }
}

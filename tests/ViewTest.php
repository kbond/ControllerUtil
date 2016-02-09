<?php

namespace Zenstruck\ControllerUtil\Tests;

use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ViewTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $view = new View('foo', 201, 'bar', array('foo' => 'baz'), array('baz' => 'foo'));

        $this->assertSame('foo', $view->getData());
        $this->assertSame(201, $view->getStatusCode());
        $this->assertSame('bar', $view->getTemplate());
        $this->assertSame(array('foo' => 'baz'), $view->getCache());
        $this->assertSame(array('baz' => 'foo'), $view->getHeaders());
    }

    public function testCreateCached()
    {
        $view = View::createCached('foo', 10);

        $this->assertSame(array('s_maxage' => 10), $view->getCache());
    }

    /**
     * @dataProvider dataAsArrayProvider
     */
    public function testGetDataAsArray($data, $expected)
    {
        $view = new View($data);

        $this->assertSame($expected, $view->getDataAsArray());
    }

    public function testArrayAccess()
    {
        $view = new View('foo');
        $view['bar'] = 'baz';

        $this->assertSame(array('data' => 'foo', 'bar' => 'baz'), $view->getData());
        $this->assertSame('foo', $view['data']);
        $this->assertSame(array('data' => 'foo', 'bar' => 'baz'), $view->getDataAsArray());
        $this->assertTrue(isset($view['data']));

        $view['data'] = 'buz';
        unset($view['bar']);

        $this->assertSame(array('data' => 'buz'), $view->getData());
    }

    public function dataAsArrayProvider()
    {
        $object = new \stdClass();

        return array(
            array(null, array()),
            array('foo', array('data' => 'foo')),
            array(array('foo' => 'bar'), array('foo' => 'bar')),
            array($object, array('data' => $object)),
        );
    }
}

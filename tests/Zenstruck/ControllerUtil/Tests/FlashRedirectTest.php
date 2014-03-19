<?php

namespace Zenstruck\ControllerUtil\Tests;

use Zenstruck\ControllerUtil\FlashRedirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FlashRedirectTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $redirect = FlashRedirect::create('foo', array('bar' => 'baz'), 'bar');

        $this->assertSame('foo', $redirect->getRoute());
        $this->assertSame(array('info' => array('bar')), $redirect->getFlashes());
    }

    public function testCreateSimple()
    {
        $redirect = FlashRedirect::createSimple('foo', 'bar');

        $this->assertSame('foo', $redirect->getRoute());
        $this->assertSame(array('info' => array('bar')), $redirect->getFlashes());
    }
}

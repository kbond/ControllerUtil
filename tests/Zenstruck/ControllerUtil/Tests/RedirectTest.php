<?php

namespace Zenstruck\ControllerUtil\Tests;

use Zenstruck\ControllerUtil\Redirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RedirectTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $redirect = new Redirect('foo', array('bar' => 'baz'), 301);

        $this->assertSame('foo', $redirect->getRoute());
        $this->assertSame(array('bar' => 'baz'), $redirect->getParameters());
        $this->assertSame(301, $redirect->getStatusCode());
    }
}

<?php

namespace Zenstruck\ControllerUtil\Tests;

use Zenstruck\ControllerUtil\Forward;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ForwardTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $forward = new Forward('foo', array('foo' => 'bar'));

        $this->assertSame('foo', $forward->getController());
        $this->assertSame(array('foo' => 'bar'), $forward->getParameters());
    }
}

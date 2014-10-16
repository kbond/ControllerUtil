<?php

namespace Zenstruck\ControllerUtil\Tests\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Zenstruck\ControllerUtil\ParamConverter\FlashBagParamConverter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FlashBagParamConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetObject()
    {
        $flashBag = new FlashBag();
        $converter = new FlashBagParamConverter($flashBag);

        $object = $converter->getObject(new Request());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Session\Flash\FlashBag', $object);
        $this->assertSame($object, $flashBag);
    }

    public function testSupports()
    {
        $flashBag = new FlashBag();
        $converter = new FlashBagParamConverter($flashBag);

        $this->assertTrue($converter->supports('Symfony\Component\HttpFoundation\Session\Flash\FlashBag'));
        $this->assertTrue($converter->supports('Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface'));
        $this->assertFalse($converter->supports('foo'));
    }
}

<?php

namespace Zenstruck\ControllerUtil\Tests\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Zenstruck\ControllerUtil\ParamConverter\SessionParamConverter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SessionParamConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetObject()
    {
        $converter = new SessionParamConverter();
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $object = $converter->getObject($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Session\Session', $object);
        $this->assertSame($object, $session);
    }

    public function testSupports()
    {
        $converter = new SessionParamConverter();

        $this->assertTrue($converter->supports('Symfony\Component\HttpFoundation\Session\Session'));
        $this->assertTrue($converter->supports('Symfony\Component\HttpFoundation\Session\SessionInterface'));
        $this->assertFalse($converter->supports('foo'));
    }
}

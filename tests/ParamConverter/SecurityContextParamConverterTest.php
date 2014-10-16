<?php

namespace Zenstruck\ControllerUtil\Tests\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Zenstruck\ControllerUtil\ParamConverter\SecurityContextParamConverter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SecurityContextParamConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetObject()
    {
        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $converter = new SecurityContextParamConverter($securityContext);

        $object = $converter->getObject(new Request());

        $this->assertInstanceOf('Symfony\Component\Security\Core\SecurityContextInterface', $object);
        $this->assertSame($object, $securityContext);
    }

    public function testSupports()
    {
        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $converter = new SecurityContextParamConverter($securityContext);

        $this->assertTrue($converter->supports('Symfony\Component\Security\Core\SecurityContextInterface'));
        $this->assertFalse($converter->supports('foo'));
    }
}

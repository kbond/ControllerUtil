<?php

namespace Zenstruck\ControllerUtil\Tests\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Zenstruck\ControllerUtil\ParamConverter\FormFactoryParamConverter;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class FormFactoryParamConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetObject()
    {
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $converter = new FormFactoryParamConverter($formFactory);

        $object = $converter->getObject(new Request());

        $this->assertInstanceOf('Symfony\Component\Form\FormFactoryInterface', $object);
        $this->assertSame($object, $formFactory);
    }

    public function testSupports()
    {
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $converter = new FormFactoryParamConverter($formFactory);

        $this->assertTrue($converter->supports('Symfony\Component\Form\FormFactoryInterface'));
        $this->assertFalse($converter->supports('foo'));
    }
}

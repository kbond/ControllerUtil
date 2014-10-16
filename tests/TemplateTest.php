<?php

namespace Zenstruck\ControllerUtil\Tests;

use Zenstruck\ControllerUtil\Template;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateCached()
    {
        $template = Template::createCached('foo', 20, array('foo' => 'bar'));

        $this->assertSame('foo', $template->getTemplate());
        $this->assertSame(array('foo' => 'bar'), $template->getData());
        $this->assertSame(array('s_maxage' => 20), $template->getCache());
    }

    public function testConstructor()
    {
        $template = new Template('foo', array('foo' => 'bar'));

        $this->assertSame('foo', $template->getTemplate());
        $this->assertSame(array('foo' => 'bar'), $template->getData());
    }
}

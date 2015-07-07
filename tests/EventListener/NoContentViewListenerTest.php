<?php

namespace Zenstruck\ControllerUtil\Tests\EventListener;

use Zenstruck\ControllerUtil\EventListener\NoContentViewListener;
use Zenstruck\ControllerUtil\View;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NoContentViewListenerTest extends BaseListenerTest
{
    public function test_empty_view_result_sets_no_content_response()
    {
        $listener = new NoContentViewListener();
        $event = $this->createEvent(new View(null));
        $this->assertNull($event->getResponse());

        $listener->onKernelView($event);
        $response = $event->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertSame('', $response->getContent());
        $this->assertSame(204, $response->getStatusCode());
    }

    /**
     * @dataProvider nonEmptyViewProvider
     */
    public function test_non_empty_view_result_does_nothing(View $view)
    {
        $listener = new NoContentViewListener();
        $event = $this->createEvent($view);
        $this->assertNull($event->getResponse());
        $listener->onKernelView($event);
        $this->assertNull($event->getResponse());
    }

    public function test_null_view_result_sets_no_content_response_with_allow_null_true()
    {
        $listener = new NoContentViewListener();
        $event = $this->createEvent(null);
        $this->assertNull($event->getResponse());

        $listener->onKernelView($event);
        $response = $event->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertSame('', $response->getContent());
        $this->assertSame(204, $response->getStatusCode());
    }

    public function test_null_view_result_does_nothing_with_allow_null_false()
    {
        $listener = new NoContentViewListener(false);
        $event = $this->createEvent(null);
        $this->assertNull($event->getResponse());

        $listener->onKernelView($event);
        $this->assertNull($event->getResponse());
    }

    public static function nonEmptyViewProvider()
    {
        return array(
            array(new View('foo')),
            array(new View(null, 200, 'foo')),
        );
    }
}

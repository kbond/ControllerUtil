# ControllerUtil

[![Build Status](https://travis-ci.org/kbond/ControllerUtil.png?branch=master)](https://travis-ci.org/kbond/ControllerUtil)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kbond/ControllerUtil/badges/quality-score.png?s=8ccf58e68bf3d17a5914d45bb3864fab5bff2082)](https://scrutinizer-ci.com/g/kbond/ControllerUtil/)
[![Code Coverage](https://scrutinizer-ci.com/g/kbond/ControllerUtil/badges/coverage.png?s=a2c65d675c282a838904c21d0624dbe71fe99e71)](https://scrutinizer-ci.com/g/kbond/ControllerUtil/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/480f9e60-4ae0-46ea-b691-b66e51aa09f4/mini.png)](https://insight.sensiolabs.com/projects/480f9e60-4ae0-46ea-b691-b66e51aa09f4)
[![Latest Stable Version](https://poser.pugx.org/zenstruck/controller-util/v/stable.png)](https://packagist.org/packages/zenstruck/controller-util)
[![Latest Unstable Version](https://poser.pugx.org/zenstruck/controller-util/v/unstable.png)](https://packagist.org/packages/zenstruck/controller-util)
[![License](https://poser.pugx.org/zenstruck/controller-util/license.png)](https://packagist.org/packages/zenstruck/controller-util)

When creating Symfony2 controllers as services, you often require the same dependencies for every controller.

You often need:

* The router for generating redirects.
* The session for adding flash messages.
* The templating engine for creating views.
* The kernel for forwarding to another controller.
* If using [jms/serializer](https://github.com/schmittjoh/serializer), the serializer.

This library aims to remove those common dependencies by enabling your controllers to return small immutable
objects for these tasks. View listeners then take those objects and create the response.

There is a [Symfony2 Bundle](https://github.com/kbond/ZenstruckControllerUtilBundle) and a
[Silex Service Provider](https://github.com/kbond/ControllerUtilServiceProvider) available to ease integration
into your project.

## Usage

### Forward

To forward the request to another controller, return the `Zenstruck\ControllerUtil\Forward` object.

```php
use Zenstruck\ControllerUtil\Forward;

// ...
public function forwardAction()
{
    return new Forward('another.controller:anotherAction', array('foo' => 'bar'));
}
// ...
```

Arguments:

* `$controller`: the controller to forward to (*required*).
* `$parameters`: an array of parameters to pass to the controller (default: `array()`).

### Redirect

To redirect to another route, return the `Zenstruck\ControllerUtil\Redirect` object.

```php
use Zenstruck\ControllerUtil\Redirect;

// ...
public function redirectAction()
{
    return new Redirect('my_route');

    // with parameters
    return new Redirect('my_route', array('foo' => 'bar'));
}
// ...
```

Arguments:

* `$route`: the route to redirect to (*required*).
* `$parameters`: an array of parameters required by the route (default: `array()`).
* `$statusCode`: the status code for the response (default: `302`).

### FlashRedirect

To redirect to another route and add a flash message, return the
`Zenstruck\ControllerUtil\FlashRedirect` object.

```php
use Zenstruck\ControllerUtil\FlashRedirect;

// ...
public function redirectAction()
{
    return new FlashRedirect('my_route', array('foo' => 'bar'), array('info' => array('Success!'));

    // factory methods
    return FlashRedirect::create('my_route', array('foo' => 'bar'), 'Error', 'error');
    return FlashRedirect::createSimple('my_route', 'Success');
}
// ...
```

Arguments:

* `$route`: the route to redirect to (*required*).
* `$parameters`: an array of parameters required by the route (default: `array()`).
* `$flashes`: an array of flash messages (default: `array()`).
* `$statusCode`: the status code for the response (default: `302`).

**NOTE**: The flashes must be an array in the following format: `array($key => array($message))`. As this
can be cumbersome, there are factory methods.

Factory Methods:

* `FlashRedirect::create`:

    Arguments:

    * `$route`: the route to redirect to (*required*).
    * `$parameters`: an array of parameters required by the route (*required*).
    * `$message`: The flash message (*required*).
    * `$type`: The flash type (default: `info`).
    * `$statusCode`: the status code for the response (default: `302`).

* `FlashRedirect::createSimple` (for redirects with no route parameters):

    Arguments:

    * `$route`: the route to redirect to (*required*).
    * `$message`: The flash message (*required*).
    * `$type`: The flash type (default: `info`).
    * `$statusCode`: the status code for the response (default: `302`).

### View

To create a view for your response, return the `Zenstruck\ControllerUtil\View` object.

```php
use Zenstruck\ControllerUtil\View;

// ...
public function viewAction()
{
    $object = // ..

    return new View($object, 200);

    // with templates
    return new View($object, 200, 'my_template.html.twig');

    // with an array of fallback templates
    return new View($object, 200, array('my_template.html.twig', 'fallback_template.html.twig'));

    // factory methods
    return View::createCached($object, 86400);
}
// ...
```

Arguments:

* `$data`: the data to pass to the view.
* `$statusCode`: the status code for the response (default: `200`).
* `$template`: the template or an array of templates (default: `null`).
* `$cache`: an array of cache options for the response (default: `array()`).
* `$headers`: an array of response headers (default: `array()`).

Factory Methods:

* `View::createCached`:

    Arguments:

    * `$data`: the data to pass to the view (*required*).
    * `$sharedMaxAge`: the shared max age in seconds (*required*).
    * `$statusCode`: the status code for the response (default: `200`).

**NOTES**:

* When `$template` is an array of templates, the view listener will loop through them and render the first one
that exists.
* If no template is provided, you need to have the `SerializerViewListener` enabled and the request must be
non-html. Otherwise an error will result.
* If `$data` is not an array, a template is provided and the request is html, the view listener will convert
`$data` to `array('data' => $data)` before passing it to your template.

### Template

If your views always have a template, you can use the `Zenstruck\ControllerUtil\Template` object for convenience.

```php
use Zenstruck\ControllerUtil\Template;

// ...
public function viewAction()
{
    $object = // ..

    return new Template('my_template.html.twig', array('object' => $object));
}
// ...
```

Arguments:

* `$template`: the template or an array of templates (*required*).
* `$parameters`: the parameters to pass to the view (default: `array()`).
* `$statusCode`: the status code for the response (default: `200`).
* `$cache`: an array of cache options for the response (default: `array()`).
* `$headers`: an array of response headers (default: `array()`).

Factory Methods:

* `Template::createCached`:

    Arguments:

    * `$template`: the template or an array of templates (*required*).
    * `$sharedMaxAge`: the shared max age in seconds (*required*).
    * `$parameters`: the parameters to pass to the view (default: `array()`).
    * `$statusCode`: the status code for the response (default: `200`).

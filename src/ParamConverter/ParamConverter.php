<?php

namespace Zenstruck\ControllerUtil\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

/**
 * Converts request parameters to objects, so they can be injected as
 * controller method arguments.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @see https://github.com/sensiolabs/SensioFrameworkExtraBundle
 */
interface ParamConverter
{
    /**
     * Returns the object to add to the request.
     *
     * @param Request $request The request
     *
     * @return object
     */
    public function getObject(Request $request);

    /**
     * Checks if the class is supported.
     *
     * @param string $class The parameter class
     *
     * @return bool True if the object is supported, else false
     */
    public function supports($class);
}

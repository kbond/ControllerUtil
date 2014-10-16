<?php

namespace Zenstruck\ControllerUtil\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SessionParamConverter implements ParamConverter
{
    /**
     * {@inheritdoc}
     */
    public function getObject(Request $request)
    {
        return $request->getSession();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($class)
    {
        return is_subclass_of($class, "Symfony\\Component\\HttpFoundation\\Session\\SessionInterface") ||
            $class === "Symfony\\Component\\HttpFoundation\\Session\\SessionInterface";
    }
}

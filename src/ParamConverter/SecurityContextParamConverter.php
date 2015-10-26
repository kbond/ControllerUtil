<?php

namespace Zenstruck\ControllerUtil\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SecurityContextParamConverter implements ParamConverter
{
    private $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject(Request $request)
    {
        return $this->securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($class)
    {
        return is_subclass_of($class, 'Symfony\\Component\\Security\\Core\\SecurityContextInterface') ||
            $class === 'Symfony\\Component\\Security\\Core\\SecurityContextInterface';
    }
}

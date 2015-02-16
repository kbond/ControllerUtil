<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Zenstruck\ControllerUtil\ParamConverter\ParamConverter;

/**
 * Convert the request parameters into objects when type-hinted.
 *
 * This replicates the SensioFrameworkExtraBundle behavior but keeps it simple
 * and without a dependency to allow usage outside Symfony Framework apps
 * (Silex, ..).
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @see https://github.com/QafooLabs/QafooLabsNoFrameworkBundle
 * @see https://github.com/sensiolabs/SensioFrameworkExtraBundle
 */
class ParamConverterListener
{
    private $paramConverters;

    /**
     * @param ParamConverter[] $paramConverters
     */
    public function __construct(array $paramConverters = array())
    {
        $this->paramConverters = $paramConverters;
    }

    /**
     * @param ParamConverter $paramConverter
     */
    public function addParamConverter(ParamConverter $paramConverter)
    {
        $this->paramConverters[] = $paramConverter;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        if (is_array($controller)) {
            $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif ($controller instanceof \Closure) {
            $reflection = new \ReflectionFunction($controller);
        } else {
            // use __invoke
            $reflection = new \ReflectionMethod(get_class($controller), '__invoke');
        }

        foreach ($reflection->getParameters() as $param) {
            if (!$param->getClass() || $param->getClass()->isInstance($request)) {
                continue;
            }

            $this->apply($request, $param);
        }
    }

    /**
     * @param Request              $request
     * @param \ReflectionParameter $param
     */
    private function apply(Request $request, \ReflectionParameter $param)
    {
        $class = $param->getClass()->getName();

        foreach ($this->paramConverters as $paramConverter) {
            if (!$paramConverter->supports($class)) {
                continue;
            }

            $request->attributes->set($param->getName(), $paramConverter->getObject($request));

            break;
        }
    }
}

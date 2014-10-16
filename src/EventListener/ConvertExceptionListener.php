<?php

namespace Zenstruck\ControllerUtil\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Converts Exceptions into Symfony HttpKernel Exceptions.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @see https://github.com/QafooLabs/QafooLabsNoFrameworkBundle
 */
class ConvertExceptionListener
{
    private $exceptionClassMap;

    /**
     * @param array $exceptionClassMap Class as the key, HTTP status code as the value
     */
    public function __construct(array $exceptionClassMap = array())
    {
        $this->exceptionClassMap = $exceptionClassMap;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            return;
        }

        $statusCode = $this->findStatusCode($exception);

        if (null === $statusCode) {
            return;
        }

        $event->setException(new HttpException($statusCode, null, $exception));
    }

    /**
     * @param \Exception $exception
     *
     * @return int|null
     */
    private function findStatusCode(\Exception $exception)
    {
        $exceptionClass = get_class($exception);

        foreach ($this->exceptionClassMap as $originalExceptionClass => $statusCode) {
            if ($exceptionClass === $originalExceptionClass || is_subclass_of($exceptionClass, $originalExceptionClass)) {
                return (int) $statusCode;
            }
        }

        return null;
    }
}

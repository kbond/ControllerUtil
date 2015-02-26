<?php

namespace Zenstruck\ControllerUtil\Exception;

/**
 * Have exceptions implement this to provide a safe message to send
 * to the user.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface HasSafeMessage
{
    /**
     * @return string
     */
    public function getSafeMessage();
}

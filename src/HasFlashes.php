<?php

namespace Zenstruck\ControllerUtil;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface HasFlashes
{
    const DEFAULT_FLASH_KEY = 'info';

    /**
     * Returns the array of flashes.
     *
     * Example:
     *
     *     array(
     *         'info' => array(
     *             'Thank you.'
     *         )
     *     )
     *
     * @return array
     */
    public function getFlashes();
}

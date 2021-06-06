<?php

namespace BlancoHugo\Blog\Twig\Globals;

/**
 * Twig global variables data provider interface
 *
 * @package BlancoHugo\Blog\Twig\Globals
 */
interface GlobalProviderInterface
{
    /**
     * Returns the global variable data
     *
     * @return mixed
     */
    public function __invoke();
}

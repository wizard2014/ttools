<?php

namespace TTools\Provider\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for TTools Package
 *
 * @author Aran Wilkinson <aran@aranw.net>
 * @package TTools\Provider\Laravel
 */
class TToolsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ttools';
    }
}
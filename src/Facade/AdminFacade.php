<?php
namespace Dosarkz\Lora\Facade;

use Illuminate\Support\Facades\Facade;

class AdminFacade extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin';
    }
}
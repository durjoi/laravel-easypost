<?php
namespace Saperemarketing\Phpmailer\Facades;

use Illuminate\Support\Facades\Facade;

class Mailer extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'saperemail';
    }
}

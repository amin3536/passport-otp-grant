<?php

namespace Amin3536\PassportOtpGrant\Facades;

use Illuminate\Support\Facades\Facade;

class PassportOtpGrant extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'passport-otp-grant';
    }
}

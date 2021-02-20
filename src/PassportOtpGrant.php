<?php

namespace Amin3536\PassportOtpGrant;

use App\Modules\otpGrant\OTPGrant;
use App\Modules\otpGrant\OTPRepository;
use DateInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class PassportOtpGrant extends ServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app
            ->afterResolving(AuthorizationServer::class, function (AuthorizationServer $server) {
                $server->enableGrantType($this->makeOTPGrant(), DateInterval::createfromdatestring('+1 day'));
            });
    }

    /**
     * Create and configure a OTP grant instance.
     *
     * @return OTPGrant
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    protected function makeOTPGrant()
    {
        $grant = new OTPGrant(
            $this->app->make(OTPRepository::class),
            $this->app->make(RefreshTokenRepository::class),
            new DateInterval('PT10M')
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}

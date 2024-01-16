<?php
/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/14/21
 * Time: 12:19 PM.
 */

namespace Amin3536\PassportOtpGrant\otpGrant;

use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Component\String\Exception\RuntimeException;

class OTPRepository implements OTPRepositoryInterFace
{
    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($phoneNumber, $otp, $grantType, ClientEntityInterface $clientEntity)
    {
        $provider = $clientEntity->provider ?: config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.' . $provider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        if (method_exists($model, 'validateForOTPCodeGrant')) {
            $user = (new $model)->validateForOTPCodeGrant($phoneNumber, $otp);

            if (!$user) {
                return;
            }

            return new User($user->getAuthIdentifier());
        }
    }
}

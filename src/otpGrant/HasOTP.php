<?php
/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/14/21
 * Time: 2:29 PM.
 */

namespace App\Modules\otpGrant;

use League\OAuth2\Server\Exception\OAuthServerException;

trait HasOTP
{
    public $phoneNumberColumn = 'phone_number';

    protected function getPhoneNumberColumn()
    {
        return $this->phoneNumberColumn;
    }

    public $OTPColumn = 'otp';

    protected function getOTPColumn()
    {
        return $this->OTPColumn;
    }

    public $OTPExpireTime = 15;

    protected function getOTPExpireTime()
    {
        return $this->OTPExpireTime;
    }

    /**
     * @param $phoneNumber
     * @param $otp
     * @return mixed
     */
    public function validateForOTPCodeGrant($phoneNumber, $otp)
    {
        $user = $this->where($this->getPhoneNumberColumn(), $phoneNumber)->first();

        if (! $user) {
            throw OAuthServerException::invalidRequest('phone_number', 'phone_number');
        }

        if (! $user->otp || $user->otp != $otp) {
            throw OAuthServerException::invalidRequest('otp', 'otp is wrong ');
        }

        if ($user->updated_at->diff(now())->format('%i min') > $this->getOTPExpireTime()) {
            throw  OAuthServerException::invalidRequest('otp', 'otp code expired try  get it  again');
        }
        $this->removeOtp($user);

        return $user;
    }

    public function removeOtp($user)
    {
        $user->save([$this->getOTPColumn() => null]);
    }
}

<?php

namespace Amin3536\PassportOtpGrant\otpGrant;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface OTPRepositoryInterFace
{
    public function getUserEntityByUserCredentials($phoneNumber, $otp, $grantType, ClientEntityInterface $clientEntity);
}

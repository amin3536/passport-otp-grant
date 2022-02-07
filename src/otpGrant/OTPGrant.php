<?php
/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/14/21
 * Time: 12:14 PM.
 */

namespace Amin3536\PassportOtpGrant\otpGrant;

use DateInterval;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class OTPGrant extends AbstractGrant
{
    /**
     * @var DateInterval
     */
    private $authCodeTTL;

    public $OTPRepository;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function __construct(
        OTPRepositoryInterFace $OTPRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        DateInterval $authCodeTTL
    ) {
        $this->OTPRepository = $OTPRepository;
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->authCodeTTL = $authCodeTTL;
        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ) {

        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new access token
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));
        $responseType->setAccessToken($accessToken);

        // Issue and persist new refresh token if given
        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));
            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;
    }

    /**
     * @param  ServerRequestInterface  $request
     * @param  ClientEntityInterface  $client
     * @return UserEntityInterface
     *
     * @throws OAuthServerException
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $phoneNumber = $this->getRequestParameter('phone_number', $request);

        if (\is_null($phoneNumber)) {
            throw OAuthServerException::invalidRequest('phone_number');
        }

        $otp = $this->getRequestParameter('otp', $request);

        if (\is_null($otp)) {
            throw OAuthServerException::invalidRequest('otp');
        }

        $user = $this->OTPRepository->getUserEntityByUserCredentials(
            $phoneNumber,
            $otp,
            $this->getIdentifier(),
            $client
        );

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidGrant();
        }

        return $user;
    }

    public function getIdentifier()
    {
        return 'otp_grant';
    }
}

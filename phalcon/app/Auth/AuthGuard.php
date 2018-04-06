<?php namespace Phasty\Auth;

use Carbon\Carbon;
use Phasty\HTTPException;
use Phasty\Models\Users;
use Phalcon\Mvc\User\Plugin;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Protocol\Version2;
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\Purpose;
use ParagonIE\Paseto\Rules\IssuedBy;
use ParagonIE\Paseto\Rules\NotExpired;
use Phasty\Units\User\IUserProvider;

class AuthGuard extends Plugin
{

    /**
     * @var IUserProvider
     */
    private $provider;

    /**
     * @var Users|null
     */
    private $user;

    /**
     * @var JsonToken|null
     */
    private $token;


    /**
     * PasetoAuthGuard constructor.
     * @param IUserProvider $provider
     */
    public function __construct(IUserProvider $provider)
    {
        $this->provider = $provider;
    }


    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     * @throws HTTPException
     * @throws PasetoException
     * @throws TypeError
     * @throws \ParagonIE\Paseto\Exception\InvalidVersionException
     * @throws \TypeError
     */
    public function check()
    {
        $parser = Parser::getLocal($this->getSharedKey(), ProtocolCollection::v2());

        $parser->addRule(new NotExpired);
        $parser->addRule(new IssuedBy($this->getIssuer()));

        try {
            $this->token = $parser->parse($this->getTokenFromRequest());
        } catch (PasetoException $e) {
            throw new HTTPException(
                'Not Authorized',
                401,
                array(
                    'dev'          => 'You are not allowed to enter this area',
                    'internalCode' => 'NF1000',
                    'more'         => $e->getMessage(),
                )
            );
        }

        return true;
    }

    /**
     * Determine if the current request is a guest user.
     *
     * @return bool
     * @throws HTTPException
     * @throws PasetoException
     * @throws TypeError
     * @throws \ParagonIE\Paseto\Exception\InvalidVersionException
     * @throws \TypeError
     */
    public function guest()
    {
        if ($this->user !== null) {
            return false;
        }

        return ! $this->check();
    }

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param array $credentials
     *
     * @return mixed
     * @throws PasetoException
     * @throws \ParagonIE\Paseto\Exception\InvalidKeyException
     * @throws \ParagonIE\Paseto\Exception\InvalidPurposeException
     * @throws \TypeError
     */
    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            return $this->login($user);
        }

        return false;
    }

    /**
     * Get the currently authenticated user.
     *
     * @param $withPassword bool
     * @return \Phasty\Models\Users
     * @throws PasetoException
     */
    public function user($withPassword = false)
    {
        if ( ! $this->user && $this->token) {
            $this->user = $this->provider->retrieveById($this->token->get('id'));
        }
        if ($withPassword) {
            return $this->user;
        }
        $user           = $this->user;
        $user->password = '';

        return $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return $this->provider->validateCredentials($this->user, $credentials);
    }

    /**
     * Set the current user.
     *
     * @param  Users $user
     * @return $this
     */
    public function setUser(Users $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param $user
     * @return string
     * @throws PasetoException
     * @throws \ParagonIE\Paseto\Exception\InvalidKeyException
     * @throws \ParagonIE\Paseto\Exception\InvalidPurposeException
     * @throws \TypeError
     */
    private function login($user)
    {
        $this->setUser($user);

        return $this->generateTokenForUser();
    }

    /**
     * @return string
     * @throws PasetoException
     * @throws \ParagonIE\Paseto\Exception\InvalidKeyException
     * @throws \ParagonIE\Paseto\Exception\InvalidPurposeException
     * @throws \TypeError
     */
    private function generateTokenForUser()
    {
        $claims = [
            'id' => $this->user->id
        ];

        $token = $this->getTokenBuilder()
            ->setExpiration(Carbon::now()->addHours($this->getExpireTime()))
            ->setIssuer($this->getIssuer())
            ->setClaims($claims);

        return $token->toString();
    }

    /**
     * @return SymmetricKey
     * @throws \TypeError
     */
    private function getSharedKey()
    {
        return SymmetricKey::fromEncodedString(getenv('PASETO_AUTH_KEY'));
    }

    /**
     * @return string|bool
     */
    private function getTokenFromRequest()
    {
        if ($token = $this->request->getHeader('Authorization')) {
            return array_reverse(explode('Bearer ', $token, 2))[0];
        }

        return false;
    }

    /**
     * @return mixed
     */
    private function getIssuer()
    {
        return getenv('PASETO_AUTH_ISSUER');
    }

    /**
     * @return mixed
     */
    private function getExpireTime()
    {
        return getenv('PASETO_AUTH_EXPIRE_AFTER_HOURS');
    }

    /**
     * @return Builder
     * @throws PasetoException
     * @throws \ParagonIE\Paseto\Exception\InvalidKeyException
     * @throws \ParagonIE\Paseto\Exception\InvalidPurposeException
     * @throws \TypeError
     */
    private function getTokenBuilder()
    {
        return (new Builder)
            ->setKey($this->getSharedKey())
            ->setVersion(new Version2)
            ->setPurpose(Purpose::local());
    }
}
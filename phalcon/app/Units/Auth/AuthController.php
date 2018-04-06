<?php namespace Phasty\Units\Auth;

use Centrifugo\Centrifugo;
use League\Pipeline\Pipeline;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\SanitizeInput;
use Phasty\Units\User\Read\ShowUser;

class AuthController extends Plugin
{

    public function login()
    {
        $credentials = (new SanitizeInput($this->store->requestBody))->sanitize(['email', 'password']);

        $token       = $this->auth->attempt($credentials);

        if ( ! $token) {
            throw new HTTPException(
                'Not Authorized',
                401,
                array(
                    'dev'  => 'You are not allowed to enter this area. token not generated',
                    'more' => '',
                )
            );
        }

        return ['access_token' => $token];
    }

    public function getUserInfo()
    {
        /**
         * @var $payload \stdClass
         */
        $payload =  (new Pipeline())
            ->pipe(new ShowUser())
            ->pipe(new CentrifugoInfo())
            ->process($this->payload->create([
                'id' => $this->auth->user()->id
            ]));
        return $payload->result;
    }

    public function logout()
    {

    }
}
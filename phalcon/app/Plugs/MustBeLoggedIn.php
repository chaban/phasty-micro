<?php namespace Phasty\Plugs;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\User\Plugin;

class MustBeLoggedIn extends Plugin implements MiddlewareInterface
{
    public function beforeExecuteRoute()
    {
        if ($this->router->getMatchedRoute()->getName() !== 'auth-login') {
            return $this->auth->check();
        }
    }

    /**
     * Calls the middleware
     *
     * @param Micro $app
     *
     * @returns bool
     */
    public function call(Micro $app)
    {
        return true;
    }
}


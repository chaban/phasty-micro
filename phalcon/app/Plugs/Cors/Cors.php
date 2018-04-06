<?php

namespace Phasty\Plugs\Cors;

use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;

class Cors extends Plugin implements MiddlewareInterface
{
    /** @var \Phasty\Plugs\Cors\ICors */
    protected $corsProfile;

    public function __construct(ICors $corsProfile)
    {
        $this->corsProfile = $corsProfile;
    }

    public function beforeHandleRoute()
    {
        if (! $this->isCorsRequest()) {
            return false;
        }

        if (! $this->corsProfile->isAllowed()) {
            return $this->forbiddenResponse();
        }

        if ($this->isPreflightRequest()) {
            return $this->handlePreflightRequest();
        }

        $this->corsProfile->addCorsHeaders();
    }

    protected function isCorsRequest(): bool
    {
        if (! $this->request->getHeader('ORIGIN')) {
            return false;
        }

        return $this->request->getHeader('ORIGIN') !== $this->request->getScheme() . '://' . $this->request->getHttpHost();
    }

    protected function isPreflightRequest(): bool
    {
        return $this->request->getMethod() === 'OPTIONS';
    }

    protected function handlePreflightRequest()
    {
        if (! $this->corsProfile->isAllowed()) {
            return $this->forbiddenResponse();
        }

        return $this->corsProfile->addPreflightHeaders();
    }

    protected function forbiddenResponse()
    {
        throw new HTTPException(
            'Forbidden (cors).',
            403,
            array(
                'dev' => 'That route was not found on the server.',
                'internalCode' => 'NF1000',
                'more' => 'Check route for misspellings.'
            )
        );
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

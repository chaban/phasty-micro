<?php namespace Phasty\Plugs;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;

class MustBeAdmin extends Plugin implements StageInterface
{

    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        if($this->auth->user()->role !== 'admin'){
            throw new HTTPException(
                'Not Authorized',
                401,
                array(
                    'dev' => 'You are not allowed to enter this area'
                )
            );
        }
        return $payload;
    }
}
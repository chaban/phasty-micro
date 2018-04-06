<?php namespace Phasty\Units\User\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Users;

class CreateUser extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        $user = new Users();
        if ($user->create($payload->credentials)) {
            $payload->id = $user->id;
            $this->cent->publish('test', ['message', 'new user created with id ' . $payload->id]);
            return $payload;
        } else {
            throw new HTTPException(
                'could not create user.',
                400,
                array(
                    'dev' => '',
                )
            );
        }
    }
}
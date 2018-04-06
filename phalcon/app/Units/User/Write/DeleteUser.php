<?php namespace Phasty\Units\User\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Users;

class DeleteUser extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return boolean
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            $user = Users::findFirst($payload->id);
            return $user->delete();
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not delete user.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}
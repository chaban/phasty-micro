<?php namespace Phasty\Units\User\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Users;

class UpdateUser extends Plugin implements StageInterface
{
    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            $user = Users::findFirst($payload->id);
            $user->update($payload->credentials);
            $this->getDI()->get('modelsCache')->delete('user_id_' . (int)$payload->id);
            return $payload;
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not update user.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}
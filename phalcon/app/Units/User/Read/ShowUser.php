<?php namespace Phasty\Units\User\Read;

use League\Pipeline\StageInterface;
use Phasty\HTTPException;
use Phasty\Models\Users;
use Phasty\Units\User\UsersPresenter;
use Phasty\Phractal;

class ShowUser implements StageInterface
{

    /**
     * @param \stdClass $payload
     * @return object
     * @throws HTTPException
     * @throws \Exception
     */
    public function __invoke($payload)
    {
        /**
         * @param $model Users
         */
        $user = Users::query()->columns(['id', 'name', 'email', 'role', 'profile'])->where('id = :id:')
            ->bind(['id' => $payload->id])->cache(['key' => 'user_id_' . (int)$payload->id])
            ->execute()->getFirst();

        if(!$user){
            throw new HTTPException(
                'Model not found.',
                400,
                array(
                    'dev' => 'Could not find user model with id ' . $payload->id,
                )
            );
        }

        $payload->result =  (new Phractal(new UsersPresenter()))->transform($user);
        return $payload;
    }

}
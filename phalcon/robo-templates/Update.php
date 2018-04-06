<?php namespace Phasty\Units\{upperName}\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\{upperPlural};

class Update{upperName} extends Plugin implements StageInterface
{
    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            $user = {upperPlural}::findFirst($payload->id);
            $user->update($payload->credentials);
            return $payload;
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not update {name}.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}


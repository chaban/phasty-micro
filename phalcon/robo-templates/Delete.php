<?php namespace Phasty\Units\{upperName}\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\{upperPlural};

class Delete{upperName} extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return boolean
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            ${name} = {upperPlural}::findFirst($payload->id);
            return ${name}->delete();
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not delete {name}.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}


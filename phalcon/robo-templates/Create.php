<?php namespace Phasty\Units\{upperName}\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\{upperPlural};

class Create{upperName} extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        ${name} = new {upperPlural}();
        if (${name}->create($payload->credentials)) {
            $payload->id = ${name}->id;
            return $payload;
        } else {
            throw new HTTPException(
                'could not create {name}.',
                400,
                array(
                    'dev' => '',
                )
            );
        }
    }
}


<?php namespace Phasty\Units\{upperName}\Read;

use League\Pipeline\StageInterface;
use Phasty\HTTPException;
use Phasty\Models\{upperPlural};
use Phasty\Units\{upperName}\{upperPlural}Presenter;
use Phasty\Phractal;

class Show{upperName} implements StageInterface
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
        ${name} = {upperPlural}::findFirst($payload->id);

        if(!${name}){
            throw new HTTPException(
                'Model not found.',
                400,
                array(
                    'dev' => 'Could not find {name} model with id ' . $payload->id,
                )
            );
        }

        return (new Phractal(new {upperPlural}Presenter()))->transform(${name});
    }

}


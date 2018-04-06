<?php namespace Phasty\Units\Category\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Categories;

class UpdateCategory extends Plugin implements StageInterface
{
    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            $user = Categories::findFirst($payload->id);
            $user->update($payload->credentials);
            return $payload;
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not update category.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}


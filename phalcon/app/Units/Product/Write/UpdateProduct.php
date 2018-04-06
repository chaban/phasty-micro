<?php namespace Phasty\Units\Product\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Products;

class UpdateProduct extends Plugin implements StageInterface
{
    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            $user = Products::findFirst($payload->id);
            $user->update($payload->credentials);
            return $payload;
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not update product.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}


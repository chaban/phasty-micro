<?php namespace Phasty\Units\Product\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Products;

class CreateProduct extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return \stdClass
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        $product = new Products();
        if ($product->create($payload->credentials)) {
            $payload->id = $product->id;
            return $payload;
        } else {
            throw new HTTPException(
                'could not create product.',
                400,
                array(
                    'dev' => '',
                )
            );
        }
    }
}


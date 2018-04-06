<?php namespace Phasty\Units\Product\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Products;

class DeleteProduct extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return boolean
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        try {
            $product = Products::findFirst($payload->id);
            return $product->delete();
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not delete product.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }
}


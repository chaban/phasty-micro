<?php namespace Phasty\Units\Product\Read;

use League\Pipeline\StageInterface;
use Phasty\HTTPException;
use Phasty\Models\Products;
use Phasty\Units\Product\ProductsPresenter;
use Phasty\Phractal;

class ShowProduct implements StageInterface
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
        $product = Products::findFirst($payload->id);

        if(!$product){
            throw new HTTPException(
                'Model not found.',
                400,
                array(
                    'dev' => 'Could not find product model with id ' . $payload->id,
                )
            );
        }

        return (new Phractal(new ProductsPresenter()))->transform($product);
    }

}


<?php namespace Phasty\Units\Product;

use Phasty\Phractal\Presenter\FractalPresenter;

/**
 * Class ProductsPresenter
 */
class ProductsPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return ProductsTransformer
     */
    public function getTransformer()
    {
        return new ProductsTransformer();
    }
}
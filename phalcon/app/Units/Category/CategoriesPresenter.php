<?php namespace Phasty\Units\Category;

use Phasty\Phractal\Presenter\FractalPresenter;

/**
 * Class CategoriesPresenter
 */
class CategoriesPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return CategoriesTransformer
     */
    public function getTransformer()
    {
        return new CategoriesTransformer();
    }
}
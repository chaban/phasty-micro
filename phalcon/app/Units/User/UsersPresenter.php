<?php namespace Phasty\Units\User;

use Phasty\Phractal\Presenter\FractalPresenter;

/**
 * Class UsersPresenter
 */
class UsersPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UsersTransformer();
    }
}
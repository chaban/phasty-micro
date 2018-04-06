<?php namespace Phasty\Units\{upperName};

use Phasty\Phractal\Presenter\FractalPresenter;

/**
 * Class {upperPlural}Presenter
 */
class {upperPlural}Presenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return {upperPlural}Transformer
     */
    public function getTransformer()
    {
        return new {upperPlural}Transformer();
    }
}
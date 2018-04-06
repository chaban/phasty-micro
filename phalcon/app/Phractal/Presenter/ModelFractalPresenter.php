<?php
namespace Phasty\Phractal\Presenter;

use Exception;
use Phasty\Phractal\Transformer\ModelTransformer;

/**
 * Class ModelFractalPresenter
 * @package Chaban\PhalconPresenter\Presenter
 */
class ModelFractalPresenter extends FractalPresenter
{

    /**
     * Transformer
     *
     * @return ModelTransformer
     * @throws Exception
     */
    public function getTransformer()
    {
        if (!class_exists('League\Fractal\Manager')) {
            throw new Exception("Package required. Please install: 'composer require league/fractal' ");
        }

        return new ModelTransformer();
    }
}

<?php namespace Phasty\Phractal\Transformer;

use League\Fractal\TransformerAbstract;
use Phasty\Phractal\Transformable;

/**
 * Class ModelTransformer
 * @package Chaban\PhalconPresenter\Transformer
 */
class ModelTransformer extends TransformerAbstract
{
    public function transform(Transformable $model)
    {
        return $model->transform();
    }
}

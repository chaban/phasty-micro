<?php
namespace Phasty\Phractal\Contracts;

/**
 * Interface CriteriaInterface
 * @package Chaban\PhalconPresenter\Contracts
 */
interface CriteriaInterface
{
    /**
     * Apply criteria in query
     *
     * @param $model
     * @param $phractal
     *
     * @return mixed
     */
    public function apply($model, $phractal);
}

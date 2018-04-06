<?php
namespace Phasty\Phractal\Contracts;

/**
 * Interface PresenterInterface
 * @package Chaban\PhalconPresenter\Contracts
 */
interface PresenterInterface
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}

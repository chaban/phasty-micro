<?php
namespace Phasty\Phractal\Contracts;

/**
 * Interface Presentable
 * @package Chaban\PhalconPresenter\Contracts
 */
interface Presentable
{
    /**
     * @param PresenterInterface $presenter
     *
     * @return mixed
     */
    public function setPresenter(PresenterInterface $presenter);

    /**
     * @return mixed
     */
    public function presenter();
}

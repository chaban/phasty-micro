<?php namespace Phasty;

use Phalcon\Mvc\Model;
use Phasty\Phractal\Contracts\CriteriaInterface;
use Phasty\Phractal\Contracts\PresentableInterface;
use Phasty\Phractal\Contracts\PresenterInterface;

/**
 * Class Phractal
 * @package Chaban\PhalconPresenter
 */
Class Phractal
{

    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * @var PresenterInterface
     */
    protected $presenter;

    /**
     * @var bool
     */
    protected $skipPresenter = false;


    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }


    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }


    /**
     * todo implement
     * Find data by Criteria
     *
     * @param Model $model
     *
     * @param CriteriaInterface $criteria
     *
     * @return mixed
     */
    public function getByCriteria(Model $model ,CriteriaInterface $criteria)
    {
        $temp_model = $criteria->apply($model, $this);
        $results = $temp_model->get();

        return $this->transform($results);
    }


    /**
     * Skip Presenter Wrapper
     *
     * @param bool $skip
     *
     * @return $this
     */
    public function skipPresenter($skip = true)
    {
        $this->skipPresenter = $skip;

        return $this;
    }

    /**
     * Wrapper result data
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function transform($result)
    {
        if ($this->presenter instanceof PresenterInterface) {

            if (!$this->skipPresenter) {
                return $this->presenter->present($result);
            }
        }

        return $result;
    }

}
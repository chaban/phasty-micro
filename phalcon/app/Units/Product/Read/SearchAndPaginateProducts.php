<?php namespace Phasty\Units\Product\Read;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\User\Plugin;
use Phasty\Models\Products;
use Phasty\Phractal;
use Phasty\Units\Product\ProductsPresenter;
use Phalcon\Paginator\Factory;
use Phalcon\Paginator\Adapter\Model as ModelPaginator;

final class SearchAndPaginateProducts extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return array
     * @throws \Exception
     */
    public function __invoke($payload = null)
    {
        if (isset($payload) && isset($payload->builder) && $payload->builder instanceof Builder) {

            return (new Phractal(new ProductsPresenter()))->transform($this->paginate($payload->builder));
        }

        $result = Products::query()->execute();

        return (new Phractal(new ProductsPresenter()))->transform($this->paginate($result));
    }

    private function paginate($payload)
    {
        $page    = $this->request->getQuery('page', 'int', 1);
        $limit   = $this->request->getQuery('limit', 'int', 10);
        $limit   = ($limit > $this->config->searchCriteria->params->maxLimit) ? $this->config->searchCriteria->params->maxLimit : $limit;
        $options = [
            'limit'   => $limit,
            'page'    => $page
        ];

        if($payload instanceof Builder) {
            $options['adapter'] = 'queryBuilder';
            $options['builder'] = $payload;
            return Factory::load($options);
        }
        if($payload instanceof Resultset){
            $options['data'] = $payload;
            return new ModelPaginator($options);
        }
        return $payload;
    }

}

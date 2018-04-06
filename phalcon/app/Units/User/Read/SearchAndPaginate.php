<?php namespace Phasty\Units\User\Read;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\User\Plugin;
use Phasty\Models\Users;
use Phasty\Phractal;
use Phasty\Units\User\UsersPresenter;
use Phalcon\Paginator\Factory;
use Phalcon\Paginator\Adapter\Model as ModelPaginator;

final class SearchAndPaginate extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return array
     * @throws \Exception
     */
    public function __invoke($payload = null)
    {
        if (isset($payload) && isset($payload->builder) && $payload->builder instanceof Builder) {

            return (new Phractal(new UsersPresenter()))->transform($this->paginate($payload->builder));
        }

        $result = Users::find(['columns' => 'id, name, email, role, profile, created_at, updated_at']);

        return (new Phractal(new UsersPresenter()))->transform($this->paginate($result));
    }

    private function paginate($data)
    {
        $page    = $this->request->getQuery('page', 'int', 1);
        $limit   = $this->request->getQuery('limit', 'int', 10);
        $limit   = ($limit > $this->config->searchCriteria->params->maxLimit) ? $this->config->searchCriteria->params->maxLimit : $limit;
        $options = [
            'limit'   => $limit,
            'page'    => $page
        ];

        if($data instanceof Builder) {
            $options['adapter'] = 'queryBuilder';
            $options['builder'] = $data;
            return Factory::load($options);
        }
        if($data instanceof Resultset){
            $options['data'] = $data;
            return new ModelPaginator($options);
        }
        return $data;
    }

}
<?php namespace Phasty\Units\{upperName}\Read;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\User\Plugin;
use Phasty\Models\{upperPlural};
use Phasty\Phractal;
use Phasty\Units\{upperName}\{upperPlural}Presenter;
use Phalcon\Paginator\Factory;
use Phalcon\Paginator\Adapter\Model as ModelPaginator;

final class SearchAndPaginate{upperPlural} extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return array
     * @throws \Exception
     */
    public function __invoke($payload = null)
    {
        if (isset($payload) && isset($payload->builder) && $payload->builder instanceof Builder) {

            return (new Phractal(new {upperPlural}Presenter()))->transform($this->paginate($payload->builder));
        }

        $result = {upperPlural}::find(['columns' => 'id, name, email, role, profile, created_at, updated_at']);

        return (new Phractal(new {upperPlural}Presenter()))->transform($this->paginate($result));
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

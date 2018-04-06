<?php
namespace Phasty\Phractal\Presenter;

use Exception;
use Phalcon\Mvc\Model\Resultset as PhalconCollection;
use Phalcon\Paginator\Adapter as PaginatorAdapter;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\SerializerAbstract;
use Phasty\Phractal\Contracts\PresenterInterface;
use Phasty\Phractal\Pagination\PhalconPaginatorAdapter;
use Phalcon\Mvc\User\Plugin;

/**
 * Class FractalPresenter
 * @package Chaban\PhalconPresenter\Presenter
 */
abstract class FractalPresenter extends Plugin implements PresenterInterface
{
    /**
     * @var string
     */
    protected $resourceKeyItem = null;

    /**
     * @var string
     */
    protected $resourceKeyCollection = null;

    /**
     * @var \League\Fractal\Manager
     */
    protected $fractal = null;

    /**
     * @var \League\Fractal\Resource\Collection
     */
    protected $resource = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!class_exists('League\Fractal\Manager')) {
            throw new Exception("Package required. Please install: 'composer require league/fractal' ");
        }

        $this->fractal = new Manager();
        //$this->parseIncludes();
        $this->setupSerializer();
    }

    /**
     * @return $this
     */
    protected function setupSerializer()
    {
        $serializer = $this->serializer();

        if ($serializer instanceof SerializerAbstract) {
            $this->fractal->setSerializer(new $serializer());
        }

        return $this;
    }

    /**
     * todo implement
     * @return $this
     */
    protected function parseIncludes()
    {
        $paramIncludes = isset($this->config->repository->fractal->params->include) ?
            $this->config->repository->fractal->params->include : 'include';

        if ($this->request->has($paramIncludes)) {
            $this->fractal->parseIncludes($this->request->getQuery($paramIncludes));
        }

        return $this;
    }

    /**
     * Get Serializer
     *
     * @return SerializerAbstract
     */
    public function serializer()
    {
        $serializer = isset($this->config->repository->fractal->serializer) ?
            $this->config->repository->fractal->serializer : 'League\\Fractal\\Serializer\\DataArraySerializer';

        return new $serializer();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    abstract public function getTransformer();

    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     * @throws Exception
     */
    public function present($data)
    {
        if (!class_exists('League\Fractal\Manager')) {
            throw new Exception("Package required. Please install: 'composer require league/fractal' ");
        }

        if ($data instanceof PhalconCollection) {
            $this->resource = $this->transformCollection($data);
        } elseif ($data instanceof PaginatorAdapter) {
            $this->resource = $this->transformPaginator($data);
        } else {
            $this->resource = $this->transformItem($data);
        }

        return $this->fractal->createData($this->resource)->toArray();
    }

    /**
     * @param $data
     *
     * @return Item
     */
    protected function transformItem($data)
    {
        return new Item($data, $this->getTransformer(), $this->resourceKeyItem);
    }

    /**
     * @param $data
     *
     * @return \League\Fractal\Resource\Collection
     */
    protected function transformCollection($data)
    {
        return new Collection($data, $this->getTransformer(), $this->resourceKeyCollection);
    }

    /**
     * @param PaginatorAdapter $paginator
     *
     * @return \League\Fractal\Resource\Collection
     */
    protected function transformPaginator(PaginatorAdapter $paginator)
    {
        $pager = $paginator->getPaginate();
        $collection = collect($pager->items);
        $resource = new Collection($collection, $this->getTransformer(), $this->resourceKeyCollection);
        $resource->setPaginator(new PhalconPaginatorAdapter($pager));

        return $resource;
    }
}

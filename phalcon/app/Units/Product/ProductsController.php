<?php
namespace Phasty\Units\Product;

use League\Pipeline\Pipeline;
use Phalcon\Mvc\User\Plugin;
use Phasty\RequestCriteria;
use Phasty\Plugs\MustBeAdmin;
use Phasty\Units\Product\Read\SearchAndPaginateProducts;
use Phasty\Models\Products;
use Phasty\Units\Product\Read\ShowProduct;
use Phasty\Units\Product\Validators\OnUpdateProduct as ValidateOnUpdateProduct;
use Phasty\Units\Product\Validators\OnCreateProduct as ValidateOnCreateProduct;
use Phasty\Units\Product\Write\CreateProduct;
use Phasty\Units\Product\Write\DeleteProduct;
use Phasty\Units\Product\Write\UpdateProduct;

class ProductsController extends Plugin
{

    protected $fieldSearchable = [
        'name' => 'like'
    ];

    public function index()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new RequestCriteria)
            ->pipe(new SearchAndPaginateProducts())
            ->process($this->payload->create([
                'model'            => Products::class,
                'fieldsSearchable' => $this->fieldSearchable
            ]));
    }

    public function show(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ShowProduct())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }

    public function create()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnCreateProduct())
            ->pipe(new CreateProduct())
            ->pipe(new ShowProduct())
            ->process(new \stdClass());
    }

    public function update(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnUpdateProduct())
            ->pipe(new UpdateProduct())
            ->pipe(new ShowProduct())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }

    public function delete(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new DeleteProduct())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }
}


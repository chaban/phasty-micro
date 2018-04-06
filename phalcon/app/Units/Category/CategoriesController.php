<?php
namespace Phasty\Units\Category;

use League\Pipeline\Pipeline;
use Phalcon\Mvc\User\Plugin;
use Phasty\Units\Category\Read\GetAllCategories;
use Phasty\Plugs\MustBeAdmin;
use Phasty\Units\Category\Read\ShowCategory;
use Phasty\Units\Category\Validators\OnUpdateCategory as ValidateOnUpdateCategory;
use Phasty\Units\Category\Validators\OnCreateCategory as ValidateOnCreateCategory;
use Phasty\Units\Category\Write\CreateCategory;
use Phasty\Units\Category\Write\DeleteCategory;
use Phasty\Units\Category\Write\UpdateCategory;

class CategoriesController extends Plugin
{


    public function index()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new GetAllCategories())
            ->process($this->payload->create([
                'getAs' => 'tree'
            ]));
    }

    public function show(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ShowCategory())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }

    public function create()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnCreateCategory())
            ->pipe(new CreateCategory())
            ->process(new \stdClass());
    }

    public function update(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnUpdateCategory())
            ->pipe(new UpdateCategory())
            ->pipe(new ShowCategory())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }

    public function delete(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new DeleteCategory())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }
}


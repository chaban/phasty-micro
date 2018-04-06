<?php
namespace Phasty\Units\{upperName};

use League\Pipeline\Pipeline;
use Phalcon\Mvc\User\Plugin;
use Phasty\RequestCriteria;
use Phasty\Plugs\MustBeAdmin;
use Phasty\Units\{upperName}\Read\SearchAndPaginate{upperPlural};
use Phasty\Models\{upperPlural};
use Phasty\Units\{upperName}\Read\Show{upperName};
use Phasty\Units\{upperName}\Validators\OnUpdate{upperName} as ValidateOnUpdate{upperName};
use Phasty\Units\{upperName}\Validators\OnCreate{upperName} as ValidateOnCreate{upperName};
use Phasty\Units\{upperName}\Write\Create{upperName};
use Phasty\Units\{upperName}\Write\Delete{upperName};
use Phasty\Units\{upperName}\Write\Update{upperName};

class {upperPlural}Controller extends Plugin
{

    protected $fieldSearchable = [
        'name' => 'like'
    ];

    public function index()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new RequestCriteria)
            ->pipe(new SearchAndPaginate{upperPlural}())
            ->process($this->payload->create([
                'model'            => {upperPlural}::class,
                'fieldsSearchable' => $this->fieldSearchable
            ]));
    }

    public function show(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new Show{upperName}())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }

    public function create()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnCreate{upperName}())
            ->pipe(new Create{upperName}())
            ->pipe(new Show{upperName}())
            ->process(new \stdClass());
    }

    public function update(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnUpdate{upperName}())
            ->pipe(new Update{upperName}())
            ->pipe(new Show{upperName}())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }

    public function delete(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new Delete{upperName}())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }
}


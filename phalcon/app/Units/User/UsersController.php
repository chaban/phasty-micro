<?php
namespace Phasty\Units\User;

use League\Pipeline\Pipeline;
use Phalcon\Mvc\User\Plugin;
use Phasty\RequestCriteria;
use Phasty\Plugs\MustBeAdmin;
use Phasty\Units\User\Read\SearchAndPaginate;
use Phasty\Models\Users;
use Phasty\Units\User\Read\ShowUser;
use Phasty\Units\User\Validators\OnUpdateUser as ValidateOnUpdateUser;
use Phasty\Units\User\Validators\OnCreateUser as ValidateOnCreateUser;
use Phasty\Units\User\Write\CreateUser;
use Phasty\Units\User\Write\DeleteUser;
use Phasty\Units\User\Write\UpdateUser;

class UsersController extends Plugin
{

    protected $fieldSearchable = [
        'name' => 'like',
        'email', // Default Condition "="
    ];

    public function index()
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new RequestCriteria)
            ->pipe(new SearchAndPaginate())
            ->process($this->payload->create([
                'model'            => Users::class,
                'fieldsSearchable' => $this->fieldSearchable
            ]));
    }

    public function show(int $id)
    {
        $payload =  (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ShowUser())
            ->process($this->payload->create([
                'id' => $id
            ]));
        return $payload->result;
    }

    public function create()
    {
        $payload = (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnCreateUser())
            ->pipe(new CreateUser())
            ->pipe(new ShowUser())
            ->process(new \stdClass());
        return $payload->result;
    }

    public function update(int $id)
    {
        $payload = (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new ValidateOnUpdateUser())
            ->pipe(new UpdateUser())
            ->pipe(new ShowUser())
            ->process($this->payload->create([
                'id' => $id
            ]));
        return $payload->result;
    }

    public function delete(int $id)
    {
        return (new Pipeline())
            ->pipe(new MustBeAdmin())
            ->pipe(new DeleteUser())
            ->process($this->payload->create([
                'id' => $id
            ]));
    }
}
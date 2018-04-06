<?php
use Phasty\Units\User\UsersController;
return call_user_func(function ()  {

    $routeCollection = new \Phalcon\Mvc\Micro\Collection();

    $routeCollection
        ->setPrefix('/users')
        ->setHandler(UsersController::class)
        ->setLazy(true);

    $routeCollection->get('/', 'index', 'admin-all-users');
    // This is exactly the same execution as GET, but the Response has no body.
    $routeCollection->head('/', 'index');

    // $id will be passed as a parameter to the Controller's specified function
    $routeCollection->get('/{id:[0-9]+}', 'show', 'admin-show-user');
    $routeCollection->head('/{id:[0-9]+}', 'show');
    $routeCollection->post('/', 'create', 'admin-create-user');
    $routeCollection->delete('/{id:[0-9]+}', 'delete', 'admin-delete-user');
    $routeCollection->put('/{id:[0-9]+}', 'update', 'admin-update-user');
    $routeCollection->patch('/{id:[0-9]+}', 'update', 'admin-patch-user');

    return $routeCollection;
});

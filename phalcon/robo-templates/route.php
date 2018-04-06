<?php
use Phasty\Units\{upperName}\{upperPlural}Controller;
return call_user_func(function ()  {

    $routeCollection = new \Phalcon\Mvc\Micro\Collection();

    $routeCollection
        ->setPrefix('/{plural}')
        ->setHandler({upperPlural}Controller::class)
        ->setLazy(true);

    $routeCollection->get('/', 'index', 'admin-all-{plural}');
    // This is exactly the same execution as GET, but the Response has no body.
    $routeCollection->head('/', 'index');

    // $id will be passed as a parameter to the Controller's specified function
    $routeCollection->get('/{id:[0-9]+}', 'show', 'admin-show-{name}');
    $routeCollection->head('/{id:[0-9]+}', 'show}');
    $routeCollection->post('/', 'create', 'admin-create-{name}');
    $routeCollection->delete('/{id:[0-9]+}', 'delete', 'admin-delete-{name}');
    $routeCollection->put('/{id:[0-9]+}', 'update', 'admin-update-{name}');
    $routeCollection->patch('/{id:[0-9]+}', 'update', 'admin-patch-{name}');

    return $routeCollection;
});



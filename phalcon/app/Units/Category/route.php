<?php
use Phasty\Units\Category\CategoriesController;
return call_user_func(function ()  {

    $routeCollection = new \Phalcon\Mvc\Micro\Collection();

    $routeCollection
        ->setPrefix('/categories')
        ->setHandler(CategoriesController::class)
        ->setLazy(true);

    $routeCollection->get('/', 'index', 'admin-all-categories');
    // This is exactly the same execution as GET, but the Response has no body.
    $routeCollection->head('/', 'index');

    // $id will be passed as a parameter to the Controller's specified function
    $routeCollection->get('/{id:[0-9]+}', 'show', 'admin-show-category');
    $routeCollection->head('/{id:[0-9]+}', 'show}');
    $routeCollection->post('/', 'create', 'admin-create-category');
    $routeCollection->delete('/{id:[0-9]+}', 'delete', 'admin-delete-category');
    $routeCollection->put('/{id:[0-9]+}', 'update', 'admin-update-category');
    $routeCollection->patch('/{id:[0-9]+}', 'update', 'admin-patch-category');

    return $routeCollection;
});



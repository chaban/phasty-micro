<?php
use Phasty\Units\Product\ProductsController;
return call_user_func(function ()  {

    $routeCollection = new \Phalcon\Mvc\Micro\Collection();

    $routeCollection
        ->setPrefix('/products')
        ->setHandler(ProductsController::class)
        ->setLazy(true);

    $routeCollection->get('/', 'index', 'admin-all-products');
    // This is exactly the same execution as GET, but the Response has no body.
    $routeCollection->head('/', 'index');

    // $id will be passed as a parameter to the Controller's specified function
    $routeCollection->get('/{id:[0-9]+}', 'show', 'admin-show-product');
    $routeCollection->head('/{id:[0-9]+}', 'show}');
    $routeCollection->post('/', 'create', 'admin-create-product');
    $routeCollection->delete('/{id:[0-9]+}', 'delete', 'admin-delete-product');
    $routeCollection->put('/{id:[0-9]+}', 'update', 'admin-update-product');
    $routeCollection->patch('/{id:[0-9]+}', 'update', 'admin-patch-product');

    return $routeCollection;
});



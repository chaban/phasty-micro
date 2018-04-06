<?php
use Phasty\Units\Auth\AuthController;
return call_user_func(function () {

    $routeCollection = new \Phalcon\Mvc\Micro\Collection();

    $routeCollection
        ->setPrefix('/auth')
        ->setHandler(AuthController::class)
        ->setLazy(true);

    // $id will be passed as a parameter to the Controller's specified function
    $routeCollection->post('/login', 'login', 'auth-login');
    $routeCollection->get('/getUserInfo', 'getUserInfo', 'getUserInfo');
    $routeCollection->head('/getUserInfo', 'getUserInfo');
    $routeCollection->delete('/logout', 'logout', 'auth-logout');

    return $routeCollection;
});



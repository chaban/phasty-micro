<?php

//error_reporting(0);
error_reporting(E_ALL);
ini_set('phalcon.orm.not_null_validations', 'on');
define('BASE_DIR', dirname(__DIR__));
//(new \Phalcon\Debug)->listen();
require_once BASE_DIR . '/vendor/autoload.php';
(new Dotenv\Dotenv(BASE_DIR))->load();
include BASE_DIR . '/config/services.php';

$app = new Phalcon\Mvc\Micro();
$app->setDI($di);

if ( ! $app->getEventsManager()) {
    $app->setEventsManager($app->getDI()->get('eventsManager'));
}

/**
 * Attach global middleware
 * This middleware effects all endpoints
 **/
$eventsManager = $app->getEventsManager();
$eventsManager->attach('micro', new Phasty\Plugs\Cors\Cors(new Phasty\Plugs\Cors\DefaultProfile()));
$eventsManager->attach('micro', new Phasty\Plugs\SetUpStore());
$eventsManager->attach('micro', new Phasty\Plugs\MustBeLoggedIn());

//Mount routes
foreach (glob(BASE_DIR . '/app/Units/*/route.php') as $routeFile) {
    $app->mount(include($routeFile));
}

//send response
$app->after(function () use ($app) {

    // Respond by default as JSON
    if ( ! $app->request->get('type') || $app->request->get('type') == 'json') {

        // Results returned from the route's controller.  All Controllers should return an array
        $records = $app->getReturnedValue();

        $response = new Phasty\Response();
        $response->send($records);

        return;

    } else {
        throw new Phasty\HTTPException(
            'Could not return results in specified format',
            403,
            array(
                'dev'          => 'Could not understand type specified by type paramter in query string.',
                'internalCode' => 'NF1000',
                'more'         => 'Type may not be implemented.',
            )
        );
    }
});

//handle if route not found
$app->notFound(function () use ($app) {
    throw new Phasty\HTTPException(
        'Not Found.',
        404,
        array(
            'dev'          => 'That route was not found on the server.',
            'internalCode' => 'NF1000',
            'more'         => 'Check route for misspellings.',
        )
    );
});

$app->handle();

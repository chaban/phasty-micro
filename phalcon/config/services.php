<?php
/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */

$di     = new \Phalcon\DI\FactoryDefault();
$config = include BASE_DIR . '/config/config.php';
$di->setShared('config', $config);

$di->setShared('crypt', function () use ($config) {
    $crypt = new \Phalcon\Crypt();
    $crypt->setKey($config->app->cryptSalt);
    return $crypt;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */

$di->setShared('db', function () use ($config) {
    return new \Phalcon\Db\Adapter\Pdo\Postgresql([
        "host" => $config->db->host,
        "username" => $config->db->username,
        "password" => $config->db->password,
        "dbname" => $config->db->dbname
    ]);
});

/**
 * EasyDb service. PDO lightweight wrapper
 */
$di->setShared('edb', function () use ($config, $di) {
    /*return \ParagonIE\EasyDB\Factory::create(
        'pgsql:host='. $config->db->host . ';dbname=' . $config->db->dbname,
        $config->db->username,
        $config->db->password
    );*/
    return new \ParagonIE\EasyDB\EasyDB($di->get('db')->getInternalHandler(), 'pgsql');
});

$di->setShared('redis', function () use ($config){
    return (new \Redis())->connect($config->redis->host, $config->redis->port);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () use ($config) {
    if (isset($config->models->metadata) && $config->models->metadata->adapter == 'Redis') {
        return new \Phalcon\Mvc\Model\MetaData\Redis(array(
            "host" => $config->redis->host,
            "port" => $config->redis->port,
            "persistent" => 0,
            "prefix" => "phasty.modelsMetadata",
            "lifetime" => 172800,
            "index" => 0,
        ));
    } else {
        return new \Phalcon\Mvc\Model\Metadata\Memory();
    }
});

/**
 * Cache
 * @description Phalcon - \Phalcon\Cache\Backend\Redis
 */

$di->setShared('cache', function () use ($config) {
    //Create a Data frontend and set a default lifetime to 1 hour
    $frontend = new \Phalcon\Cache\Frontend\Data(array(
        'lifetime' => 86400,
        'prefix' => 'phasty.cache.'
    ));

    //Create the cache passing the connection
    $cache = new \Phalcon\Cache\Backend\Redis($frontend, array(
        "host" => $config->redis->host,
        "port" => $config->redis->port,
        "persistent" => false,
        "prefix" => "phasty.cache",
        "index" => 0,
    ));

    return $cache;
});

/**
 * Cache for Orm models
 */

$di->setShared('modelsCache', function () use ($config) {
    //Create a Data frontend and set a default lifetime to 1 hour
    $frontend = new \Phalcon\Cache\Frontend\Data(array(
        'lifetime' => 86400,
        'prefix' => 'phasty.modelsCache.'
    ));

    //Create the cache passing the connection
    $cache = new \Phalcon\Cache\Backend\Redis($frontend, array(
        "host" => $config->redis->host,
        "port" => $config->redis->port,
        "persistent" => false,
        "prefix" => "phasty.modelsCache",
        "index" => 0,
    ));

    return $cache;
});

/**
 * Main logger file
 */
$di->setShared('logger', function () {
    return new \Phalcon\Logger\Adapter\File(BASE_DIR . '/storage/logs/' . 'errors.log');
});

/**
 * application's dynamic store
 */
$di->setShared('store', function (){
    return new \Phalcon\Registry();
});

/**
 * Authorization
 * Return \Phasty\Auth\AuthGuard()
 */
$di->setShared('auth', function (){
    return new \Phasty\Auth\AuthGuard(new \Phasty\Units\User\UserProvider());
});
/**
 * payload variable for pipeline as stdClass object
 * $return \Phasty\Payload()
 */
$di->setShared('payload', function (){
    return new \Phasty\Payload();
});

/**
 * $return \Phasty\Payload()
 */
$di->setShared('cent', function () use($config){
    $endpoint = getenv('APP_URL');
    return new \Centrifugo\Centrifugo($endpoint, getenv('CENTRIFUGO_API_SECRET'), [
        'redis' => [
            'host'         => $config->redis->host,
            'port'         => $config->redis->port,
            'db'           => 0,
        ],
        'http' => [
            CURLOPT_TIMEOUT => 5,
        ],
    ]);
});

/**
 * Error handler
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($di) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    $di->getLogger()->log($errstr . ' ' . $errfile . ':' . $errline, \Phalcon\Logger::ERROR);
    return true;
});

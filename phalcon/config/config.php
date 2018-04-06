<?php
return new \Phalcon\Config([
    'db'   => [
        'adapter'  => getenv('DB_ADAPTER'),
        'host'     => getenv('DB_HOST'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'dbname'   => getenv('DB_DATABASE')
    ],
    'redis' => [
        'host' => getenv('REDIS_HOST'),
        'port' => getenv('REDIS_PORT')
    ],
    'app'  => [
        'debug'     => true,
        'cacheDir'  => BASE_DIR . '/storage/cache/',
        'publicUrl' => getenv('APP_URL'),
        'cryptSalt' => getenv('APP_SALT')
    ],

    'auth' => [
        'secret' => getenv('PASETO_AUTH_KEY'),
        'expirationTime' => getenv('PASETO_AUTH_EXPIRE_AFTER_HOURS'),
    ],

    'mail' => require_once 'mail.php',

    'cors' => require_once 'cors.php',

    'searchCriteria' => require_once 'searchCriteria.php',

    'models' => ['metadata' => ['adapter' => 'Redis']]
]);

<?php

require_once 'vendor/autoload.php';
(new \Dotenv\Dotenv('./'))->load();
//$config = include 'config/config.php';
return [
    "paths"        => [
        "migrations" => "db/migrations",
        "seeds"      => "db/seeds",
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database"        => "phasty",
        "phasty"                  => [
            "adapter" => "pgsql",
            "host"    => getenv('DB_HOST'),
            "name"    => getenv('DB_DATABASE'),
            "user"    => getenv('DB_USERNAME'),
            "pass"    => getenv('DB_PASSWORD'),
            "port"    => getenv('DB_PORT'),
            "charset" => "utf8"
        ]
    ]
];
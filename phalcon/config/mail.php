<?php
return [
    'viewsDir'   => BASE_DIR . '/app/views/email/',
    'driver'     => getenv('MAIL_DRIVER'),
    "host"       => getenv('MAIL_HOST'),
    "port"       => getenv('MAIL_PORT'),
    "username"   => getenv('MAIL_USERNAME'),
    "password"   => getenv('MAIL_PASSWORD'),
    'encryption' => getenv('MAIL_ENCRYPTION'),
    'from'       => [
        'email' => 'admin@example.com',
        'name'  => 'Phasty e-commerce'
    ]
];


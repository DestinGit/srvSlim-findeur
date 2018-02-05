<?php
$app->add('CorsMiddleware');


/*$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);

$app->add(new \CorsSlim\CorsSlim($corsOptions));*/

/*
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => getenv("SECRET_KEY_JWT")
]));
*/
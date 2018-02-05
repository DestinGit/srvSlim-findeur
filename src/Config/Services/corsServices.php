<?php

$container = $app->getContainer();

/*$container["CorsMiddleware"] = function ($container) {
    return new CorsMiddleware([
        "logger" => $container["logger"],
        "origin" => ["*"],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
        "headers.expose" => ["Authorization", "Etag"],
        "credentials" => true,
        "cache" => 60,
        "error" => function ($request, $response, $arguments) {
            return new UnauthorizedResponse($arguments["message"], 401);
        }
    ]);
};*/

$container['CorsMiddleware'] = function () {
    return new \CorsSlim\CorsSlim([
        "origin" => "*",
        "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
        "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
    ]);
};
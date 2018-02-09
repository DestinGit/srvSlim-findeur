<?php

use \Psr\Container\ContainerInterface;
use Tuupola\Middleware\CorsMiddleware;

//use Tuupola\Middleware\HttpBasicAuthentication;

// Injection de dépendences
$container = $app->getContainer();
$container['appConfig'] = ['appName' => getenv('APP_NAME'), 'maintenance' => getenv('APP_STATUS')];

$container['database'] = [
    'user' => 'root',
    'password' => '',
    'dsn' => 'mysql:host=127.0.0.1;dbname=findeurDBTest;charset=utf8'
];


/*$container['database'] = [
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'dsn' => getenv('DATABASE_DSN')
];*/

// Récupération de la configuration
$container['pdo'] = function (ContainerInterface $container) {
    $dsn = $container->get('database')['dsn'];

    $pdo = null;
    try {
        $pdo = new \PDO(
            $dsn,
            $container->get('database')['user'],
            $container->get('database')['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );

        // Start a transaction, disable auto-commit
        //$pdo->beginTransaction();

    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
    return $pdo;
};

/**
 * @param ContainerInterface $container
 * @return \app\DAO\UserDAO
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
$container['user.dao'] = function (ContainerInterface $container) {
    $pdo = $container->get('pdo');

    return new \app\DAO\UserDAO($pdo);
};


/**
 * @return \app\Entities\UserDTO
 */
$container['user.dto'] = function () {
    return new \app\Entities\UserDTO();
};


/**
 * @param ContainerInterface $container
 * @return \app\DAO\TextPatternDAO
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 */
$container['textpattern.dao'] = function (ContainerInterface $container) {
    $pdo = $container->get('pdo');
    return new  \app\DAO\TextPatternDAO($pdo);
};

/**
 * @return \app\Entities\TextPatternDTO
 */
$container['textpattern.dto'] = function () {
    return new \app\Entities\TextPatternDTO();
};

/**
 * @return \CorsSlim\CorsSlim
 */
$container['CorsMiddleware'] = function () {
    return new \CorsSlim\CorsSlim([
        "origin" => "*",
        "exposeHeaders" => ["Content-Type", "X-Requested-With", "X-authentication", "X-client", "Authorization"],
        "allowMethods" => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
    ]);
};

///**
// * @param $container
// * @return CorsMiddleware
// */
//$container["CorsMiddleware"] = function ($container) {
//    return new CorsMiddleware([
//        //"logger" => $container["logger"],
//        "origin" => ["*"],
//        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
//        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
//        "headers.expose" => ["Authorization", "Etag", "Content-Type", "X-Requested-With", "X-authentication", "X-client"],
//        "credentials" => true,
//        "cache" => 60,
//        "error" => function ($request, $response, $arguments) {
//            return new UnauthorizedResponse($arguments["message"], 401);
//        }
//    ]);
//};

/**
 * @param $container
 * @return StdClass
 */
$container["jwt"] = function (ContainerInterface $container) {
    return new StdClass;
};

/**
 * @param ContainerInterface $container
 * @return \Slim\Middleware\JwtAuthentication
 */
$container['JwtAuthentication'] = function (ContainerInterface $container) {
    return new \Slim\Middleware\JwtAuthentication([
        'secure' => true,
        'secret' => getenv('SECRET_KEY_JWT'),
        "rules" => [
            new \Slim\Middleware\JwtAuthentication\RequestPathRule([
                "path" => "/",
                // "passthrough" => ["/user/find", "/missions-list"]
                "passthrough" => ["/get/user", "/get/freelance-list", "/get/missions-list", "/get/test"]
            ]),
            new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
                "passthrough" => ["OPTIONS"]
            ]),
        ],
        'callback' => function (\Slim\Http\Request $request, \Slim\Http\Response $response, $arguments) use ($container) {
            $container["jwt"] = $arguments["decoded"];
        },
        'error' => function (\Slim\Http\Request $request, \Slim\Http\Response $response, $arguments) use ($container) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];

            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]);
};
/*$container['JwtAuthentication'] = function (ContainerInterface $container) {
    return new \Slim\Middleware\JwtAuthentication([
        'secure' => true,
        'secret' => getenv('SECRET_KEY_JWT'),
        "rules" => [
            new \Slim\Middleware\JwtAuthentication\RequestPathRule([
                "path" => "/",
                "passthrough" => ["/token", "/not-secure", "/home"]
            ]),
            new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
                "passthrough" => ["OPTIONS"]
            ]),
        ],
        'callback' => function (\Slim\Http\Request $request, \Slim\Http\Response $response, $arguments) use ($container) {
            $container["jwt"] = $arguments["decoded"];
        },
        'error' => function (\Slim\Http\Request $request, \Slim\Http\Response $response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]);
};*/
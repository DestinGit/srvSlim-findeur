<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 16:17
 */
date_default_timezone_set("UTC");

// Auto chargement des class
require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(dirname(__DIR__). '/src/Config/Env');
$dotenv->load();

/*$config = [
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];*/

use Slim\App;

// Instantiation du Framework
$app = new App([
    'settings' => ['displayErrorDetails' => true]
]);

//$app = new App($config);


// Chargement de la dépendence sur la configuration et la connexion à la BD
require '../src/Config/dependencies.php';

// Chargement des middlewares
// require '../src/Config/Middlewares/middlewares.php';

// Chargement des routes de l'application
require '../src/Config/Routes/routes.php';


// Lancement du Framework
try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
} catch (\Slim\Exception\NotFoundException $e) {
} catch (Exception $e) {
}
<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 16:17
 */

// Auto chargement des class
require __DIR__ . '/../vendor/autoload.php';

use Slim\App;

// Instantiation du Framework
$app = new App();


$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);

$cors = new \CorsSlim\CorsSlim($corsOptions);

$app->add($cors);


// Chargement de la dépendence sur la configuration et la connexion à la BD
//require '../src/Config/dependencies.php';
require __DIR__ . '/../src/config/dependencies.php';

// Chargement des routes de l'application
require '../src/Config/routes.php';


// Lancement du Framework
try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
} catch (\Slim\Exception\NotFoundException $e) {
} catch (Exception $e) {
}
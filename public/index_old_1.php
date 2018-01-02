<?php
// Auto chargement des class
require __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

// Instantiation du Framework
$app = new App();

// définition d'une route
/*$app->get("/hello", function(Request $request, Response $response) {
    $name = $request->getParam('name') ?? 'World';
    $response->getBody()->write("Hello $name" );
});*/

$app->get("/hello/{name}", function(Request $request, Response $response, array $args) {
    $name = $args['name'] ?? 'World';
    $response->getBody()->write("Hello $name" );
})->setName('hello');

/*$app->post('form', function(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $name = filter_var('name', FILTER_SANITIZE_STRING);

    // .... envoi de la resppnse en json

    // redirection
    return $response->withRedirect('/list');
});*/

$app->get('/products/all', function(Request $request, Response $response) {
    $data = [
        ["code"=> "A5", "price"=> 12],
        ["code"=> "B8", "price"=> 19]
    ];

    return $response->withJson($data);
});

$app->get('/home', function (Request $request, Response $response) {
    $url = $this->get('router')->pathFor('hello', ['name' => 'Alfred']);
    $link = "<a href=\"{$url}\">bonjour Alfred</a>";
    return $response->getBody()->write($link);
});


// Les routes déclarées sont préfixées par /user
$app->group("/user", function () use ($app){
    $app->get('/list', function (Request $request, Response $response) {
        //...
        return $response->withJson('list');
    });
    $app->get('/details[/{id:\d+}]', function (Request $request, Response $response, $args) {
        //...
        $id = $args['id'] ?? 1;
        return $response->withJson("details id = $id");
    });
});

// Lancement du Framework
$app->run();
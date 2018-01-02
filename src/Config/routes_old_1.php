<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 15/12/2017
 * Time: 14:46
 */
use Slim\Http\Request;
use Slim\Http\Response;
use app\Controller\UserCtrl;

// Les routes déclarées sont préfixées par /user
$app->group("/user", function () use ($app) {

/*    $app->get('/list', function (Request $request, Response $response) {

        $user = $this->get('user.dao');
        $result = $user->findAll()->getAllAsArray();

        return $response->withJson($result);
    });*/

    $app->get('/list', app\Controller\UserCtrl::class . ":getAllUsers");
    $app->get('/1/{login}/{pass}', app\Controller\UserCtrl::class . ":getOneUser");

/*    $app->get('/1/{login}/{pass}', function (Request $request, Response $response, $args) {
        $name = $args['login'];
        $pass = $args['pass'];

        $search = [
            'name' => $name,
            //'pass' => $pass
        ];

        $user = $this->get('user.dao');
        $result = $user->find($search)->getOneAsArray();

        return $response->withJson($result);
    });*/

    $app->get('/details[/{id:\d+}]', function (Request $request, Response $response, $args) {
        //...
        $id = $args['id'] ?? 1;
        return $response->withJson("details id = $id");
    });
});
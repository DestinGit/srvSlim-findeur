<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 16:17
 */

// Les routes déclarées sont préfixées par /user
$app->group("/user", function () use ($app) {

    $app->get('/list', app\Controller\UserCtrl::class . ":getAllUsers");
    $app->get('/details/{login}/{pass}', app\Controller\UserCtrl::class . ":getOneUser");
    $app->get('/find/{name}/{pass}', app\Controller\UserCtrl::class . ":findUser");
    $app->post('/find', app\Controller\UserCtrl::class . ":findUserPost");

});

// Routes les missions proposées par des freelances
$app->get('/freelance-list', app\Controller\TextPatternCtrl::class . ':getPersonalBusiness');

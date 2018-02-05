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

    $app->get('/find', app\Controller\UserCtrl::class . ":findUserPost2");
    $app->post('/find', app\Controller\UserCtrl::class . ":findUserPost2");
//    $app->post('/find', app\Controller\UserCtrl::class . ":findUserPost");
    $app->post('/add', app\Controller\UserCtrl::class . ":addUserPost");
    $app->get('/add', app\Controller\UserCtrl::class . ":addUserPost");
});

// Routes pour les services proposés par des freelances
$app->get('/freelance-list', app\Controller\TextPatternCtrl::class . ':getPersonalBusiness');

// Routes pour les missions proposées par les entreprises
$app->get('/missions-list', app\Controller\TextPatternCtrl::class . ':getListOfMissionsToApply');

// Route pour poster une annonce ou postuler à une mission
$app->group('/ar', function () use ($app) {
    $app->post('/add',app\Controller\TextPatternCtrl::class . ':applyToAMission');
    $app->post('/newmission', app\Controller\TextPatternCtrl::class . ':addNewMission');
});
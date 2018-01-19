<?php

use \Psr\Container\ContainerInterface;

// Injection de dépendences
$container = $app->getContainer();
$container['appConfig'] = ['appName' => 'Slim API', 'maintenance' => true];
$container['database'] = [
    'user' => 'root',
    'password' => '',
    'dsn' => 'mysql:host=127.0.0.1;dbname=findeurDBTest;charset=utf8'
];
/*
$container['database'] = [
    'user' => '143657',
    'password' => 'MopaoDB@01',
    'dsn' => 'mysql:host=mysql-yemeialways.alwaysdata.net;dbname=yemeialways_findeurdb;charset=utf8'
];
*/
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
        $pdo->beginTransaction();

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
<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'app\\Utils\\' => array($baseDir . '/src/Utils'),
    'app\\Middlewares\\' => array($baseDir . '/src/Middlewares'),
    'app\\Libs\\' => array($baseDir . '/src/Libs'),
    'app\\Entities\\' => array($baseDir . '/src/Models/Entities'),
    'app\\DBMA\\' => array($baseDir . '/src/Models/DBMA'),
    'app\\DAO\\' => array($baseDir . '/src/Models/DAOs'),
    'app\\Controller\\' => array($baseDir . '/src/Controllers'),
    'Tuupola\\Middleware\\' => array($vendorDir . '/tuupola/callable-handler/src', $vendorDir . '/tuupola/cors-middleware/src'),
    'Tuupola\\Http\\Factory\\' => array($vendorDir . '/tuupola/http-factory/src'),
    'Tuupola\\' => array($vendorDir . '/tuupola/base62/src'),
    'Slim\\Middleware\\' => array($vendorDir . '/tuupola/slim-jwt-auth/src'),
    'Slim\\' => array($vendorDir . '/slim/slim/Slim'),
    'Psr\\Log\\' => array($vendorDir . '/psr/log/Psr/Log'),
    'Psr\\Http\\Server\\' => array($vendorDir . '/psr/http-server-handler/src', $vendorDir . '/psr/http-server-middleware/src'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-message/src'),
    'Psr\\Container\\' => array($vendorDir . '/psr/container/src'),
    'Neomerx\\Cors\\' => array($vendorDir . '/neomerx/cors-psr7/src'),
    'Interop\\Http\\Factory\\' => array($vendorDir . '/http-interop/http-factory/src'),
    'Interop\\Container\\' => array($vendorDir . '/container-interop/container-interop/src/Interop/Container'),
    'Firebase\\JWT\\' => array($vendorDir . '/firebase/php-jwt/src'),
    'FastRoute\\' => array($vendorDir . '/nikic/fast-route/src'),
    'Dotenv\\' => array($vendorDir . '/vlucas/phpdotenv/src'),
    'CorsSlim\\' => array($vendorDir . '/palanik/corsslim'),
);

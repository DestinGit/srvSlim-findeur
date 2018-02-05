<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8704a1aeb19e4168fd5f18eb57e8204d
{
    public static $files = array (
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\Utils\\' => 10,
            'app\\Libs\\' => 9,
            'app\\Entities\\' => 13,
            'app\\DBMA\\' => 9,
            'app\\DAO\\' => 8,
            'app\\Controller\\' => 15,
        ),
        'S' => 
        array (
            'Slim\\Middleware\\' => 16,
            'Slim\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Container\\' => 14,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
            'FastRoute\\' => 10,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'C' => 
        array (
            'CorsSlim\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\Utils\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Utils',
        ),
        'app\\Libs\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Libs',
        ),
        'app\\Entities\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Models/Entities',
        ),
        'app\\DBMA\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Models/DBMA',
        ),
        'app\\DAO\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Models/DAOs',
        ),
        'app\\Controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Controllers',
        ),
        'Slim\\Middleware\\' => 
        array (
            0 => __DIR__ . '/..' . '/tuupola/slim-jwt-auth/src',
        ),
        'Slim\\' => 
        array (
            0 => __DIR__ . '/..' . '/slim/slim/Slim',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'CorsSlim\\' => 
        array (
            0 => __DIR__ . '/..' . '/palanik/corsslim',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8704a1aeb19e4168fd5f18eb57e8204d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8704a1aeb19e4168fd5f18eb57e8204d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit8704a1aeb19e4168fd5f18eb57e8204d::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}

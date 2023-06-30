<?php

namespace UserModule\App\Config;

// Create a new instance of our RouteCollection class.
$routes = \CodeIgniter\Config\Services::routes();

/**
 * V1 API 路由設定
 */
$routes->group(
    'api/v1',
    [
        'namespace' => 'UserModule\App\Controllers\V1',
    ],
    function (\CodeIgniter\Router\RouteCollection $routes) {
        $routes->post("user/service/login", "User::serviceLogin");
        $routes->post("user/service", "User::serviceSignup");
        $routes->put("user/refresh", "User::refresh");
        $routes->get("user/login", "User::login");
        $routes->post("user/login", "User::getToken");
        $routes->delete("user","User::delete");
        $routes->resource("user", [
            'controller' => 'User',
            'only' => ['show'],
            'filter' => 'privateResourceAuth'
        ]);
    }
);
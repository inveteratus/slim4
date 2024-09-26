<?php

use App\Controllers\HomeController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegisterController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app) {

    /* Guest only routes */
    $app->group('', function (RouteCollectorProxyInterface $app) {
        $app->get('/', IndexController::class)->setName('index');
        $app->map(['GET', 'POST'], '/login', LoginController::class)->setName('login');
        $app->map(['GET', 'POST'], '/register', RegisterController::class)->setName('register');
    })->add($app->getContainer()->get('guest'));

    /* Authenticated only routes */
    $app->group('', function (RouteCollectorProxyInterface $app) {
        $app->post('/logout', LogoutController::class)->setName('logout');
        $app->get('/home', HomeController::class)->setName('home');
    })->add($app->getContainer()->get('auth'));

};

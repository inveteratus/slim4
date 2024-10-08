<?php

declare(strict_types=1);

use App\Classes\Database;
use App\Classes\Validator;
use App\Extensions\CsrfExtension;
use App\Extensions\SettingsExtension;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Repositories\UserRepository;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Exception\HttpBadRequestException;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Extension\DebugExtension;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

return [

    /*
     * Application
     */

    App::class => function (ContainerInterface $ci) {
        $app = AppFactory::createFromContainer($ci);

        (require __DIR__ . '/middleware.php')($app);
        (require __DIR__ . '/routes.php')($app);

        return $app;
    },

    /*
     * Classes
     */

    Database::class => fn(ContainerInterface $ci) => new Database(
        $ci->get('settings')['DB_DSN'],
        $ci->get('settings')['DB_USER'],
        $ci->get('settings')['DB_PASSWORD'],
    ),

    Twig::class => function (ContainerInterface $ci) {
        $twig = Twig::create(__DIR__ . '/../templates', [
            'debug' => true,
            'cache' => false,
        ]);
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new CsrfExtension($ci->get('csrf')));
        $twig->addExtension(new SettingsExtension($ci->get('settings')));
        return $twig;
    },

    LoggerInterface::class => function (ContainerInterface $ci) {
        $logger = new Logger($ci->get('settings')['APP_NAME']);
        return $logger;
    },

    Validator::class => fn(ContainerInterface $ci) => new Validator(),

    /*
     * Middleware
     */

    'auth' => fn() => new AuthMiddleware(),

    'csrf' => function (ContainerInterface $ci) {
        $responseFactory = $ci->get(App::class)->getResponseFactory();
        $guard = new Guard(responseFactory: $responseFactory, persistentTokenMode: true);
        $guard->setFailureHandler(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
            throw new HttpBadRequestException($request, 'CSRF Error');
        });
        return $guard;
    },

    'guest' => fn() => new GuestMiddleware(),

    'oops' => fn(ContainerInterface $ci) => new WhoopsMiddleware([
        'enable' => true,
        'title' => $ci->get('settings')['APP_NAME'],
    ]),

    'twig' => fn(ContainerInterface $ci) => TwigMiddleware::create($ci->get(App::class), $ci->get(Twig::class)),

    /*
     * Repositories
     */

    UserRepository::class => fn(ContainerInterface $ci) => new UserRepository($ci->get(Database::class)),

    /*
     * Settings
     */

    'settings' => fn() => require __DIR__ . '/settings.php',

];

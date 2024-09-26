<?php

use App\Classes\Database;
use App\Classes\Validator;
use App\Controllers\HomeController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegisterController;
use App\Extensions\CsrfExtension;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Repositories\UserRepository;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\ArrayAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Factory;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Exception\HttpBadRequestException;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

/* Register our extra validation classes */
Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('App\\Validation\\Rules')
        ->withExceptionNamespace('App\\Validation\\Exceptions')
);

/* Create the DI container */
$container = (new ContainerBuilder())
    ->useAttributes(true)
    ->useAutowiring(true)
    ->addDefinitions([
        'config' => fn() => Dotenv::create(RepositoryBuilder::createWithNoAdapters()
            ->addWriter(ArrayAdapter::class)
            ->immutable()
            ->make(), dirname(__DIR__))->load(),

        /* Middleware */

        'csrf' => function(ContainerInterface $ci) {
            $responseFactory = $ci->get(App::class)->getResponseFactory();
            $guard = new Guard(responseFactory: $responseFactory, persistentTokenMode: true);
            $guard->setFailureHandler(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                throw new HttpBadRequestException($request, 'CSRF Error');
            });
            return $guard;
        },

        'twig' => fn(ContainerInterface $ci) => TwigMiddleware::create($ci->get(App::class), $ci->get(Twig::class)),

        /* Classes */

        Database::class => fn(ContainerInterface $ci) => new Database(
            $ci->get('config')['DB_DSN'],
            $ci->get('config')['DB_USER'],
            $ci->get('config')['DB_PASSWORD']
        ),

        Twig::class => function(ContainerInterface $ci) {
            $twig = Twig::create(__DIR__ . '/../templates', [
                'debug' => true,
                'cache' => false,
            ]);
            $twig->addExtension(new CsrfExtension($ci->get('csrf')));
            return $twig;
        },

        Validator::class => fn(ContainerInterface $ci) => new Validator(),

        /* Repositories */

        UserRepository::class => fn(ContainerInterface $ci) => new UserRepository($ci->get(Database::class)),
    ])
    ->build();

/* Get the application instance */
AppFactory::setContainer($container);
$app = AppFactory::create();
$container->set(App::class, $app);

session_start();

/* Register the middleware */
$app->add('twig');
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

/* Add the routes */
$app->group('', function (RouteCollectorProxyInterface $app) use ($container){
    /* Guest only routes */
    $app->group('', function (RouteCollectorProxyInterface $app) {
        $app->get('/', IndexController::class)->setName('index');
        $app->map(['GET', 'POST'], '/login', LoginController::class)->setName('login');
        $app->map(['GET', 'POST'], '/register', RegisterController::class)->setName('register');
    })->add($container->get(GuestMiddleware::class));

    /* Authenticated only routes */
    $app->group('', function (RouteCollectorProxyInterface $app) {
        $app->post('/logout', LogoutController::class)->setName('logout');
        $app->get('/home', HomeController::class)->setName('home');
    })->add($container->get(AuthMiddleware::class));
})->add('csrf');

/* Finally, run the application */
$app->run();

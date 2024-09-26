<?php

declare(strict_types=1);

namespace Tests;

use App\Classes\Database;
use App\Repositories\UserRepository;
use Closure;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Factory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getAppInstance(): \Slim\App
    {
        static $app;

        if (!isset($app)) {
            Factory::setDefaultInstance(
                (new Factory())
                    ->withRuleNamespace('App\\Validation\\Rules')
                    ->withExceptionNamespace('App\\Validation\\Exceptions'),
            );

            session_start();

            $app = (require __DIR__ . '/../bootstrap/app.php');
        }

        return $app;
    }

    protected function withTransactions(Closure $closure): void
    {
        $app = $this->getAppInstance();
        $db = $app->getContainer()->get(Database::class);

        $db->beginTransaction();

        $closure();

        $db->rollback();
    }

    protected function get(string $route): ResponseInterface
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();
        $path = $routeParser->urlFor($route);
        $request = $this->createRequest('GET', $path);

        return $app->handle($request);
    }

    protected function post(string $route, array $postData): ResponseInterface
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();
        $path = $routeParser->urlFor($route);
        $csrf = $app->getContainer()->get('csrf')->generateToken();
        $request = $this->createRequest('POST', $path)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withParsedBody(array_merge(
                $postData,
                [
                    'csrf_name' => $csrf['csrf_name'],
                    'csrf_value' => $csrf['csrf_value'],
                ],
            ));

        return $app->handle($request);
    }

    protected function urlFor(string $route, array $data = [], array $queryParams = []): string
    {
        $app = $this->getAppInstance();
        $routeParser = $app->getRouteCollector()->getRouteParser();

        return $routeParser->urlFor($route, $data, $queryParams);
    }

    protected function createUser(string $name = 'test', string $email = 'test@example.com', string $password = 'password'): object
    {
        $userRepository = $this->getAppInstance()->getContainer()->get(UserRepository::class);
        $id = $userRepository->createUser($name, $email, $password);

        return $userRepository->getById($id);
    }

    private function createRequest(string $method, string $path): ServerRequestInterface
    {
        $uri = new Uri(scheme: '', host: '', port: 80, path: $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        return new Request(
            method: $method,
            uri: $uri,
            headers: new Headers(),
            cookies: [],
            serverParams: [],
            body: $stream,
            uploadedFiles: [],
        );
    }
}

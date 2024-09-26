<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Factory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

class TestCase extends BaseTestCase
{
    protected function getAppInstance(): \Slim\App
    {
        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace('App\\Validation\\Rules')
                ->withExceptionNamespace('App\\Validation\\Exceptions'),
        );

        session_start();

        return (require __DIR__ . '/../bootstrap/app.php');
    }

    protected function get(string $path, array $headers = []): ServerRequestInterface
    {
        return $this->createRequest('GET', $path, $headers);
    }

    protected function post(string $path, array $postData = [], array $headers = []): ServerRequestInterface
    {
        return $this
            ->createRequest('POST', $path, $headers)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withParsedBody($postData);
    }

    private function createRequest(string $method, string $path, array $headers = []): ServerRequestInterface
    {
        $uri = new Uri(scheme: '', host: '', port: 80, path: $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $requestHeaders = new Headers();
        foreach ($headers as $key => $value) {
            $requestHeaders->addHeader($key, $value);
        }

        return new Request(
            method: $method,
            uri: $uri,
            headers: $requestHeaders,
            cookies: [],
            serverParams: [],
            body: $stream,
            uploadedFiles: [],
        );
    }
}

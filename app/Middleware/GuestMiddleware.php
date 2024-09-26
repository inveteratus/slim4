<?php

namespace App\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class GuestMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $uid = array_key_exists('uid', $_SESSION) ? (int) $_SESSION['uid'] : 0;

        if ($uid) {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $responseFactory = new ResponseFactory();
            return $responseFactory->createResponse(StatusCodeInterface::STATUS_FOUND)
                ->withHeader('Location', $routeParser->urlFor('home'));
        }

        return $handler->handle($request);
    }
}

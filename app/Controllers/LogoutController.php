<?php

namespace App\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class LogoutController
{
    public function __invoke(Request $request, Response $response): Response
    {
        session_regenerate_id(true);
        $_SESSION = [];
        session_write_close();

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        return $response
            ->withHeader('Location', $routeParser->urlFor('index'))
            ->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
}

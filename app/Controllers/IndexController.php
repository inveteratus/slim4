<?php

namespace App\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;
use Slim\Views\Twig;

class IndexController
{
    #[Inject]
    protected Twig $view;

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'index.twig');
    }
}

<?php

namespace App\Controllers;

use DI\Attribute\Inject;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
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

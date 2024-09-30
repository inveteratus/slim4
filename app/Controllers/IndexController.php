<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;
use Slim\Views\Twig;
use Twig\Error\Error;

class IndexController
{
    #[Inject]
    protected Twig $view;

    /**
     * @throws Error
     */
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'index.twig');
    }
}

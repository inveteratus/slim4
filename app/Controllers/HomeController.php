<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;
use Slim\Views\Twig;

class HomeController
{
    #[Inject]
    protected Twig $view;

    #[Inject]
    protected UserRepository $userRepository;

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'home.twig', [
            'user' => $this->userRepository->getById($request->getAttribute('uid')),
        ]);
    }
}

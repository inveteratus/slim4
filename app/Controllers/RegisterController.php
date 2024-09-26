<?php

namespace App\Controllers;

use App\Classes\Database;
use App\Classes\Validator;
use App\Repositories\UserRepository;
use DI\Attribute\Inject;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator as V;
use Slim\Psr7\Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class RegisterController
{
    #[Inject]
    protected Database $db;

    #[Inject]
    protected Twig $view;

    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected Validator $validator;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = [];
        $errors = [];

        if ($request->getMethod() == 'POST') {
            $params = (array)$request->getParsedBody();
            $errors = $this->validator->validate($params, $this->rules());

            if (!count($errors)) {
                session_regenerate_id(true);
                $_SESSION = ['uid' => $this->userRepository->createUser(...$params)];
                session_write_close();

                $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                return $response
                    ->withHeader('Location', $routeParser->urlFor('home'))
                    ->withStatus(StatusCodeInterface::STATUS_FOUND);
            }
        }

        return $this->view->render($response, 'register.twig', [
            'form' => $params,
            'errors' => $errors,
        ]);
    }

    /**
     * @return array<string,ChainedValidator>
     */
    private function rules(): array
    {
        return [
            'name' => V::stringType()->notEmpty()->length(4, 25)->absent($this->db, 'users', 'name'),
            'email' => V::stringType()->notEmpty()->email()->absent($this->db, 'users', 'email'),
            'password' => V::stringType()->notEmpty()->length(8),
        ];
    }
}

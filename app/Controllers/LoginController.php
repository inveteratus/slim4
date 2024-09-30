<?php

declare(strict_types=1);

namespace App\Controllers;

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
use Twig\Error\Error;

class LoginController
{
    #[Inject]
    protected Twig $view;
    #[Inject]
    protected UserRepository $userRepository;
    #[Inject]
    protected Validator $validator;

    /**
     * @throws Error
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $params = [];
        $errors = [];

        if ($request->getMethod() == 'POST') {
            $params = (array) $request->getParsedBody();
            $errors = $this->validator->validate($params, $this->rules());

            if (!count($errors)) {
                $user = $this->userRepository->getByEmail($params['email']);
                if ($user && password_verify($params['password'], $user->password)) {
                    session_regenerate_id(true);
                    $_SESSION = ['uid' => (int) $user->id];
                    session_write_close();

                    $routeParser = RouteContext::fromRequest($request)->getRouteParser();
                    return $response
                        ->withHeader('Location', $routeParser->urlFor('home'))
                        ->withStatus(StatusCodeInterface::STATUS_FOUND);
                }

                $errors['email'] = 'Invalid Credentials';
            }
        }

        return $this->view->render($response, 'login.twig', [
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
            'email' => V::stringType()->notEmpty()->email(),
            'password' => V::stringType()->notEmpty(),
        ];
    }
}

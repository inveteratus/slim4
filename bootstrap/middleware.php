<?php

declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Exception\HttpException;
use Slim\Views\Twig;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $app->add('csrf');
    $app->add('twig');

    $container = $app->getContainer();
    if (filter_var($container->get('settings')['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN)) {
        $app->add('oops');
    } else {
        $errorMiddleware = $app->addErrorMiddleware(false, true, true);
        $errorMiddleware->setDefaultErrorHandler(
            function (
                ServerRequestInterface $request,
                Throwable              $exception,
                bool                   $displayErrorDetails,
                bool                   $logErrors,
                bool                   $logErrorDetails,
            ) use ($app) {
                $container = $app->getContainer();

                if ($logErrors) {
                    $logger = $container->get(LoggerInterface::class);
                    $contents = array_merge(
                        ['exception' => $exception],
                        $logErrorDetails ? ['backtrace' => $exception->getTrace()] : [],
                    );
                    $logger->error($exception->getMessage(), $contents);
                }

                $code = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
                $message = 'Internal Server Error.';
                if (is_subclass_of($exception, HttpException::class)) {
                    $code = $exception->getCode();
                    $message = $exception->getMessage();
                }

                $twig = $container->get(Twig::class);
                return $twig->render($app->getResponseFactory()->createResponse($code), 'error.twig', [
                    'code' => $code,
                    'message' => $message,
                ]);
            },
        );
    }
};

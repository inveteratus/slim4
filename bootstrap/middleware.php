<?php

declare(strict_types=1);

use Slim\App;

return function (App $app) {
    $app->add('csrf');
    $app->add('twig');

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $app->add('whoops');
};

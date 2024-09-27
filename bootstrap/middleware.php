<?php

declare(strict_types=1);

use Slim\App;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $app->add('csrf');
    $app->add('twig');
    $app->add('whoops');
};

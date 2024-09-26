<?php

use Slim\App;

return function (App $app) {
    $app->add('csrf');
    $app->add('twig');

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, true, true);
};

<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Respect\Validation\Factory;
use Slim\App;

Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('App\\Validation\\Rules')
        ->withExceptionNamespace('App\\Validation\\Exceptions'),
);

return (new ContainerBuilder())
    ->useAttributes(true)
    ->useAutowiring(true)
    ->addDefinitions(__DIR__ . '/dependencies.php')
    ->build()
    ->get(App::class);

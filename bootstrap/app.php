<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\App;

return (new ContainerBuilder())
    ->useAttributes(true)
    ->useAutowiring(true)
    ->addDefinitions(__DIR__ . '/dependencies.php')
    ->build()
    ->get(App::class);

<?php

declare(strict_types=1);

use Respect\Validation\Factory;

require __DIR__ . '/../vendor/autoload.php';

/* Register our extra validation classes */
Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('App\\Validation\\Rules')
        ->withExceptionNamespace('App\\Validation\\Exceptions'),
);

session_start();

(require __DIR__ . '/../bootstrap/app.php')->run();

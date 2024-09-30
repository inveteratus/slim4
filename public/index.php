<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

session_start();

(require __DIR__ . '/../bootstrap/app.php')->run();

<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/public',
    ])
    ->withPreparedSets(psr12: true)
    ->withPhpCsFixerSets(perCS20: true)
    ->withRules([
        DeclareStrictTypesSniff::class,
    ])
    ->withRootFiles();

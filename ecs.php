<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousErrorNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousTraitNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\TraitUseDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Classes\TraitUseSpacingSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\Exception\Configuration\SuperfluousConfigurationException;

try {
    return ECSConfig::configure()
        ->withPaths([
            __DIR__ . '/app',
            __DIR__ . '/bootstrap',
            __DIR__ . '/public',
            __DIR__ . '/tests',
        ])
        ->withPreparedSets(psr12: true, docblocks: true)
        ->withPhpCsFixerSets(perCS20: true)
        ->withConfiguredRule(
            DeclareStrictTypesSniff::class,
            [
                'spacesCountAroundEqualsSign' => false,
            ],
        )
        ->withRootFiles();
} catch (SuperfluousConfigurationException $e) {
}

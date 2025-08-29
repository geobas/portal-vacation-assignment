<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/App')        // scan your app folder
    ->name('*.php')
    ->notName('*.blade.php')       // if you have templates
    ->exclude(['vendor'])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_quote' => true,
        // 'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'blank_line_before_statement' => ['statements' => ['return']],
        'no_trailing_whitespace' => true,
        'phpdoc_scalar' => true,
    ])
    ->setFinder($finder);

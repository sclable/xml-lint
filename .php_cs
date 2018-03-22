<?php

$fixers = [
    '@Symfony' => true,
    'array_syntax' => array('syntax' => 'short'),
    'combine_consecutive_unsets' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_imports' => ['sortAlgorithm' => 'length'],
    'phpdoc_indent' => false,
    'phpdoc_annotation_without_dot' => false,
    'phpdoc_no_empty_return' => false,
    'concat_space' => [
        'spacing' => 'one',
    ],
];

return PhpCsFixer\Config::create()
    ->setFinder(
      PhpCsFixer\Finder::create()
        ->in(__DIR__ . '/src')
        ->in(__DIR__ . '/tests')
        ->in(__DIR__ . '/bin')
    )
    ->setRules($fixers);

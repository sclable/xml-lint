<?php

$fixers = [
  '@Symfony' => true,
  'array_syntax' => array('syntax' => 'short'),
  'combine_consecutive_unsets' => true,
  'no_useless_else' => true,
  'no_useless_return' => true,
  'ordered_imports' => ['sort_algorithm' => 'length'],
  'concat_space' => [
    'spacing' => 'one',
  ],
  'yoda_style' => false,
  'dir_constant' => false,
  'phpdoc_indent' => false,
  'phpdoc_annotation_without_dot' => false,
  'phpdoc_no_empty_return' => true,
  'phpdoc_add_missing_param_annotation' => true,
  'phpdoc_order' => true,
  'phpdoc_types_order' => true,
  'general_phpdoc_annotation_remove' => [
    'annotations' => [
      'author',
    ],
  ],
  'void_return' => false,
  'single_trait_insert_per_statement' => false,
  'ternary_to_null_coalescing' => true,
  'pow_to_exponentiation' => false,
  'random_api_migration' => false,
  'declare_strict_types' => false,
  'phpdoc_no_alias_tag' => [
    'replacements' => [
      'type' => 'var',
      'link' => 'see',
    ],
  ],
  'header_comment' => [
    'comment_type' => 'PHPDoc',
    'header' => 'This file is part of the Sclable Xml Lint Package.

@copyright (c) ' . date('Y') . ' Sclable Business Solutions GmbH

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.',
    'location' => 'after_declare_strict',
    //'separate' => 'bottom',
  ],
];

return (new PhpCsFixer\Config())
    ->setFinder(
      PhpCsFixer\Finder::create()
        ->in(__DIR__ . '/src')
        ->in(__DIR__ . '/tests')
        ->in(__DIR__ . '/bin')
          // Note: The pattern is seen relative from one of the `->in()`
          // directories. And works for files too this way.
          ->notPath('bootstrap.php')
    )
    ->setRules($fixers);

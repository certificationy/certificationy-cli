<?php

$finder = PhpCsFixer\Finder::create()->in([
    __DIR__.'/src',
    __DIR__.'/tests',
]);

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'cast_spaces' => [
            'space' => 'single',
        ],
        'error_suppression' => [
            'mute_deprecation_error' => false,
            'noise_remaining_usages' => false,
            'noise_remaining_usages_exclude' => [],
        ],
        'function_to_constant' => false,
        'no_alias_functions' => false,
        'non_printable_character' => false,
        'phpdoc_summary' => false,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'protected_to_private' => false,
        'self_accessor' => false,
        'yoda_style' => false,
        'non_printable_character' => true,
        'phpdoc_no_empty_return' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php_cs.cache')
;
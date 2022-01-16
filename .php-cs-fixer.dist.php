<?php

use CodeIgniter\CodingStandard\CodeIgniter4;
use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->files()
    ->in(__DIR__)
    ->exclude('build')
    ->append([__FILE__]);

$overrides = [
    'global_namespace_import' => [
        'import_constants' => true,
        'import_functions' => true,
        'import_classes'   => true,
    ],
];

$options = [
    'finder'    => $finder,
    'cacheFile' => 'build/.php-cs-fixer.cache',
];

return Factory::create(new CodeIgniter4(), $overrides, $options)->forProjects();

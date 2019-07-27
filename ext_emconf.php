<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Structured Data Breadcrumb View Helper',
    'description' => 'View helper for rendering the structured data for a breadcrumb',
    'category' => 'fe',
    'state' => 'stable',
    'createDirs' => '',
    'clearCacheOnLoad' => true,
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'version' => '1.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.25-10.0.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\Sdbreadcrumb\\' => 'Classes']
    ],
];

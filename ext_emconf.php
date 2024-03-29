<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Structured Data Breadcrumb View Helper',
    'description' => 'View helper for rendering the structured data for a breadcrumb',
    'category' => 'fe',
    'state' => 'obsolete',
    'clearCacheOnLoad' => true,
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'version' => '1.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.25-11.5.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\Sdbreadcrumb\\' => 'Classes']
    ],
];

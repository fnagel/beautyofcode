<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "beautyofcode".
 *
 * Auto generated 05-09-2013 20:25
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'beautyOfCode Syntax Highlighter',
    'description' => 'This plugin provides Java-Script based, state-of-the-art, feature rich syntax highlighting by using SyntaxHighlighter or Prism. t3editor enabled.',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '3.2.1-dev',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => 0,
    'lockType' => '',
    'author' => 'Felix Nagel',
    'author_email' => 'info@felixnagel.com',
    'constraints' => [
        'depends' => [
            'php' => '5.4.0-7.2.99',
            'typo3' => '7.3.1-8.7.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            't3editor' => '',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'TYPO3\\Beautyofcode\\' => 'Classes',
        ],
    ],
];

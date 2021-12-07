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
    'version' => '5.0.0',
    'state' => 'stable',
    'author' => 'Felix Nagel',
    'author_email' => 'info@felixnagel.com',
    'constraints' => [
        'depends' => [
			'php' => '7.2.0-8.0.99',
			'typo3' => '10.4.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
            't3editor' => '',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'FelixNagel\\Beautyofcode\\' => 'Classes',
        ],
    ],
];

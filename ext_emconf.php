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

$EM_CONF[$_EXTKEY] = array(
    'title' => 'beautyOfCode Syntax Highlighter',
    'description' => 'This plugin provides Java-Script based, state-of-the-art, feature rich syntax highlighting by using SyntaxHighlighter or Prism. t3editor enabled.',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '3.3.1-mf',
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
    'constraints' => array(
        'depends' => array(
            'php' => '5.4.0-7.1.99',
            'typo3' => '7.3.1-8.7.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
            't3editor' => '',
        ),
    ),
    'autoload' => array(
        'psr-4' => array(
            'TYPO3\\Beautyofcode\\' => 'Classes',
        ),
    ),
);

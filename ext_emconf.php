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
	'version' => '2.1.1-dev',
	'dependencies' => '',
	'conflicts' => '',
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
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.4.99',
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' => array(
			'typo3' => '7.3.0',
		),
		'suggests' => array(
			't3editor' => '',
		),
	),
	'suggests' => array(
	),
	'autoload' => array(
		'psr-4' => array(
			'TYPO3\\Beautyofcode\\' => 'Classes'
		),
	),
);

?>
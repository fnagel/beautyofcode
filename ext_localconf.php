<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'ContentRenderer',
	array(
		'Content' => 'render'
	),
	array(
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'PageAssets',
	array(
		'PageAssets' => 'add'
	),
	array()
);

// BE preview
$backendPreviewHook = 'EXT:beautyofcode/Classes/Backend/Hooks/PageLayoutViewHooks.php:TYPO3\Beautyofcode\Backend\Hooks\PageLayoutViewHooks->getExtensionSummary';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_contentrenderer'][] = $backendPreviewHook;

// registry for available syntax highlighting libraries and their brushes
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['BrushDiscovery'] = array(
	'SyntaxHighlighter' => array(
		'path' => 'EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/scripts/',
		'excludePattern' => 'sh(Autoloader|Core|Legacy)\.js',
		'prefix' => 'shBrush',
		'suffix' => '.js',
		'dependencies' => array(),
	),
	'Prism' => array(
		'path' => 'EXT:beautyofcode/Resources/Public/Javascript/vendor/prism/components/',
		'excludePattern' => '.*(core|extras|\.min)\.js',
		'prefix' => 'prism-',
		'suffix' => '.js',
		'dependencies' => array(
			'bash' => 'clike',
			'c' => 'clike',
			'coffeescript' => 'javascript',
			'cpp' => 'c',
			'csharp' => 'clike',
			'go' => 'clike',
			'groovy' => 'clike',
			'java' => 'clike',
			'javascript' => 'clike',
			'php' => 'clike',
			'ruby' => 'clike',
			'scss' => 'css',
			'typoscript' => 'clike',
		),
	),
);
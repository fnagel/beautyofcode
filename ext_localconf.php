<?php
if (!defined ('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'ContentRenderer',
	array(
		'Content' => 'render'
	),
	// non-cacheable actions
	array(
	)
);

// BE preview
$backendPreviewHook = 'EXT:beautyofcode/Classes/Hooks/PageLayoutViewHooks.php:TYPO3\Beautyofcode\Hooks\PageLayoutViewHooks->getExtensionSummary';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_contentrenderer'][] = $backendPreviewHook;

// cache registration
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'] = array(
		'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend',
		'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend'
	);
}

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
	'TYPO3\\Beautyofcode\\Controller\\ContentController',
	'preRenderSignal',
	'TYPO3\\Beautyofcode\\Service\\BrushRegistryService',
	'registerBrush'
);

if (TYPO3_MODE === 'FE') {
	$pageRendererHook = 'EXT:beautyofcode/Classes/Hooks/PageRendererHooks.php';
	$pageRendererHook .= ':TYPO3\\Beautyofcode\\Hooks\\PageRendererHooks->addBrushAssets';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = $pageRendererHook;
}

// registry for available syntax highlighting libraries and their brushes
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['BrushDiscovery'] = array(
	'SyntaxHighlighter' => array(
		'path' => 'EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/scripts/',
		'excludePattern' => 'sh(BrushPlain|Autoloader|Core|Legacy)\.js',
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
?>
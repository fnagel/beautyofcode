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
$backendPreviewHook = 'EXT:beautyofcode/Classes/Hooks/PageLayoutViewHook.php:TYPO3\Beautyofcode\Hooks\PageLayoutViewHookS->getExtensionSummary';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_contentrenderer'][] = $backendPreviewHook;

// cache registration
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'] = array(
		'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend',
		'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend'
	);
}
?>
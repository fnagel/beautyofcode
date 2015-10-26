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
	),
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// BE preview
$backendPreviewHook = 'EXT:beautyofcode/Classes/Hooks/PageLayoutViewHooks.php:TYPO3\Beautyofcode\Hooks\PageLayoutViewHooks';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = $backendPreviewHook;

// cache registration
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'] = array(
		'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend',
		'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend'
	);
}
?>
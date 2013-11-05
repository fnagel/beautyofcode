<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
	$_EXTKEY,
	'Classes/Controller/class.tx_beautyofcode_pi1.php',
	'_pi1',
	'list_type',
	1
);

// BE preview
$backendPreviewHook = 'EXT:beautyofcode/Classes/Hooks/PageLayoutViewHook.php:FNagel\Beautyofcode\Hooks\PageLayoutViewHook->getExtensionSummary';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_pi1'][] = $backendPreviewHook;
?>
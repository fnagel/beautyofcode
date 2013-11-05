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

$dynamicTCEFormFields = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Utility/class.tx_beautyofcode_addFields.php';
include_once($dynamicTCEFormFields);

// BE preview
$backendPreviewHook = 'EXT:beautyofcode/Classes/Hooks/class.tx_beautyofcode_pi1_cms_layout.php:tx_beautyofcode_cms_layout->getExtensionSummary';
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['beautyofcode_pi1'][] = $backendPreviewHook;
?>
<?php
if (!defined ('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	$_EXTKEY,
	'Configuration/TypoScript/Static/',
	'beautyOfCode Syntax Highlighter'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'FNagel.' . $_EXTKEY,
	'ContentRenderer',
	'beautyofcode - Syntaxhighlighter'
);

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_contentrenderer';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	$pluginSignature,
	'FILE:EXT:' . $_EXTKEY . '/Configuration/Flexform/flexform_ds_pi1.xml'
);

if (TYPO3_MODE == 'BE') {
	$newContentElementWizardItem = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Configuration/Wizard/NewContentElementWizard.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['FNagel\\Beautyofcode\\Configuration\\Wizard\\NewContentElementWizard'] = $newContentElementWizardItem;
}
?>
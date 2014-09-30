<?php
if (!defined ('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	$_EXTKEY,
	'Configuration/TypoScript/SyntaxHighlighter/',
	'beautyOfCode (SyntaxHighlighter)'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	$_EXTKEY,
	'Configuration/TypoScript/Prism/',
	'beautyOfCode (Prism)'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'TYPO3.' . $_EXTKEY,
	'ContentRenderer',
	'beautyofcode - Syntaxhighlighter'
);

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_contentrenderer';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	$pluginSignature,
	'FILE:EXT:' . $_EXTKEY . '/Configuration/Flexform/ContentRenderer.xml'
);

if (TYPO3_MODE == 'BE') {
	$newContentElementWizardItem = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Backend/Configuration/Wizard/NewContentElementWizard.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['TYPO3\\Beautyofcode\\Backend\\Configuration\\Wizard\\NewContentElementWizard'] = $newContentElementWizardItem;
}

// this dummy data is necessary to allow the extbase data mapper to work
$TCA['tx_beautyofcode_domain_model_flexform'] = array(
	'ctrl' => array(
		'hideTable' => TRUE,
	),
	'columns' => array(
		'c_label' => array('config' => array()),
		'c_lang' => array('config' => array()),
		'c_code' => array('config' => array()),
		'c_highlight' => array('config' => array()),
		'c_collapse' => array('config' => array()),
		'c_gutter' => array('config' => array()),
		'uid' => array('config' => array()),
		'pid' => array('config' => array()),
	),
);
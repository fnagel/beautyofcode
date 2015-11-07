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

$TCA['tt_content']['columns']['CType']['config']['items'][] = array(
	'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:content_element.beautyofcode_contentrenderer',
	'beautyofcode_contentrenderer',
	'EXT:beautyofcode/Resources/Public/Images/ce_wiz.gif'
);
$TCA['tt_content']['types']['beautyofcode_contentrenderer']['showitem'] = '
		--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
		--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header;header,
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
		pi_flexform,
		bodytext,
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
		--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
		--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.visibility;visibility,
		--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.extended
';
$TCA['tt_content']['types']['beautyofcode_contentrenderer']['columnsOverrides'] = array(
	'bodytext' => array(
		'label' => 'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:code',
		'config' => array(
			'format' => 'mixed',
			'renderType' => 't3editor',
		),
	),
);
$TCA['tt_content']['types']['list']['subtypes_addlist']['beautyofcode_contentrenderer'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	'*',
	'FILE:EXT:' . $_EXTKEY . '/Configuration/Flexform/ContentRenderer.xml',
	'beautyofcode_contentrenderer'
);

if (TYPO3_MODE == 'BE') {
	$newContentElementWizardItem = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Configuration/Wizard/NewContentElementWizard.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['TYPO3\\Beautyofcode\\Configuration\\Wizard\\NewContentElementWizard'] = $newContentElementWizardItem;
}

// this dummy data is necessary to allow the extbase data mapper to work
$TCA['tx_beautyofcode_domain_model_flexform'] = array(
	'ctrl' => array(
		'hideTable' => TRUE,
	),
	'columns' => array(
		'c_label' => array('config' => array()),
		'c_lang' => array('config' => array()),
		'c_highlight' => array('config' => array()),
		'c_collapse' => array('config' => array()),
		'c_gutter' => array('config' => array()),
		'uid' => array('config' => array()),
		'pid' => array('config' => array()),
	),
);
?>
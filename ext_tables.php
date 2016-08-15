<?php

if (!defined('TYPO3_MODE')) {
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:beautyofcode/Configuration/TypoScript/pageTsConfig.ts">'
);

$TCA['tt_content']['columns']['CType']['config']['items'][] = array(
    'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:content_element.beautyofcode_contentrenderer',
    'beautyofcode_contentrenderer',
    'content-special-html',
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
    'FILE:EXT:'.$_EXTKEY.'/Configuration/Flexform/ContentRenderer.xml',
    'beautyofcode_contentrenderer'
);

// this dummy data is necessary to allow the extbase data mapper to work
$TCA['tx_beautyofcode_domain_model_flexform'] = array(
    'ctrl' => array(
        'hideTable' => true,
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

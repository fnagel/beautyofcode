<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function ($packageKey) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $packageKey,
        'Configuration/TypoScript/SyntaxHighlighter/',
        'beautyOfCode (SyntaxHighlighter)'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $packageKey,
        'Configuration/TypoScript/Prism/',
        'beautyOfCode (Prism)'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $packageKey . '/Configuration/TypoScript/pageTsConfig.ts">'
    );

    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = array(
        'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:content_element.beautyofcode_contentrenderer',
        'beautyofcode_contentrenderer',
        'content-special-html',
    );

    $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['showitem'] = '
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

    $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['columnsOverrides'] = array(
        'bodytext' => array(
            'label' => 'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:code',
            'config' => array(
                'format' => 'mixed',
                'renderType' => 't3editor',
            ),
        ),
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['beautyofcode_contentrenderer'] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:' . $packageKey.  '/Configuration/Flexform/ContentRenderer.xml',
        'beautyofcode_contentrenderer'
    );

    // this dummy data is necessary to allow the extbase data mapper to work
    // @todo Check if this is still needed
    $GLOBALS['TCA']['tx_beautyofcode_domain_model_flexform'] = array(
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

}, $_EXTKEY);

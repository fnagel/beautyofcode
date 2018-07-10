<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$packageKey = 'beautyofcode';

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
bodytext;LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:code,
--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.visibility;visibility,
--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.extended
';

$configuration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['beautyofcode'];
if (is_string($configuration)) {
    $configuration = (array)@unserialize($configuration);
} else {
    $configuration = array();
}

if (isset($configuration['enable_t3editor']) && $configuration['enable_t3editor'] == 1 &&
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3editor')
) {
    $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['columnsOverrides'] = array(
        'bodytext' => array(
            'config' => array(
                'format'     => 'mixed',
                'renderType' => 't3editor',
            ),
        ),
    );
};

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['beautyofcode_contentrenderer'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:' . $packageKey.  '/Configuration/Flexform/ContentRenderer.xml',
    'beautyofcode_contentrenderer'
);

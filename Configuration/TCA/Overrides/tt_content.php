<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use FelixNagel\Beautyofcode\Form\Element\T3editorElement;

defined('TYPO3') || die();

$packageKey = 'beautyofcode';

$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
    'label' => 'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf:content_element.beautyofcode_contentrenderer',
    'value' => 'beautyofcode_contentrenderer',
    'icon' => 'extensions-beautyofcode',
];
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['beautyofcode_contentrenderer'] = 'extensions-beautyofcode';

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

$configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('beautyofcode');

if (isset($configuration['enable_t3editor']) && $configuration['enable_t3editor'] == 1
    && ExtensionManagementUtility::isLoaded('t3editor')
) {
    $GLOBALS['TCA']['tt_content']['types']['beautyofcode_contentrenderer']['columnsOverrides'] = [
        'bodytext' => [
            'config' => [
                'format' => T3editorElement::T3EDITOR_MODE_DEFAULT,
                'renderType' => 't3editor',
            ],
        ],
    ];
}

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['beautyofcode_contentrenderer'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:' . $packageKey.  '/Configuration/Flexform/ContentRenderer.xml',
    'beautyofcode_contentrenderer'
);

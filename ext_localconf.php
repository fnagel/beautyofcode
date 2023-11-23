<?php

use FelixNagel\Beautyofcode\Controller\ContentController;
use FelixNagel\Beautyofcode\Form\Element\T3editorElement;
use FelixNagel\Beautyofcode\Hooks\PageLayoutViewHooks;
use TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(static function ($packageKey) {
    ExtensionUtility::configurePlugin(
        $packageKey,
        'ContentRenderer',
        [
            ContentController::class => 'render',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $packageKey . '/Configuration/TSconfig/page.tsconfig">'
    );

    // BE preview
    // @todo Remove this when TYPO3 v11 is no longer needed!
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] =
        PageLayoutViewHooks::class;

    $cacheConfigurations = &$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];
    // Cache registration
    if (!array_key_exists($packageKey, $cacheConfigurations) || !is_array($cacheConfigurations[$packageKey])) {
        $cacheConfigurations[$packageKey] = [];
    }

    if (!isset($cacheConfigurations[$packageKey]['backend'])) {
        $cacheConfigurations[$packageKey]['backend'] = TransientMemoryBackend::class;
    }

    if (!isset($cacheConfigurations[$packageKey]['frontend'])) {
        $cacheConfigurations[$packageKey]['frontend'] = VariableFrontend::class;
    }

    // Dynamic changing of t3editor format
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1491758504] = [
        'nodeName' => 't3editor',
        'priority' => '70',
        'class' => T3editorElement::class,
    ];
}, 'beautyofcode');

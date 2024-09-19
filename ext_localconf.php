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

    // Dynamic changing of code editor format
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1491758504] = [
        'nodeName' => 'codeEditor',
        'priority' => '70',
        'class' => T3editorElement::class,
    ];
}, 'beautyofcode');

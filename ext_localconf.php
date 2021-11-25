<?php

defined('TYPO3') || die();

call_user_func(function ($packageKey) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $packageKey,
        'ContentRenderer',
        [
			FelixNagel\Beautyofcode\Controller\ContentController::class => 'render',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $packageKey . '/Configuration/TSconfig/page.tsconfig">'
    );

    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] =
        \FelixNagel\Beautyofcode\Hooks\PageLayoutViewHooks::class;

    $cacheConfigurations = &$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];
    // Cache registration
    if (!array_key_exists($packageKey, $cacheConfigurations) || !is_array($cacheConfigurations[$packageKey])) {
        $cacheConfigurations[$packageKey] = [];
    }
    if (!isset($cacheConfigurations[$packageKey]['backend'])) {
        $cacheConfigurations[$packageKey]['backend'] = \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class;
    }
    if (!isset($cacheConfigurations[$packageKey]['frontend'])) {
        $cacheConfigurations[$packageKey]['frontend'] = \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class;
    }

    // Dynamic changing of t3editor format
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1491758504] = [
        'nodeName' => 't3editor',
        'priority' => '70',
        'class' => \FelixNagel\Beautyofcode\Form\Element\T3editorElement::class,
    ];
}, 'beautyofcode');

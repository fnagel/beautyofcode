<?php

defined('TYPO3_MODE') or die();

call_user_func(function ($packageKey) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'FelixNagel.'.$packageKey,
        'ContentRenderer',
        [
            'Content' => 'render',
        ],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] =
        \FelixNagel\Beautyofcode\Hooks\PageLayoutViewHooks::class;

    $cacheConfigurations = &$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];
    // Cache registration
    if (!is_array($cacheConfigurations['cache_beautyofcode'])) {
        $cacheConfigurations['cache_beautyofcode'] = [];
    }
    if (!isset($cacheConfigurations['cache_beautyofcode']['backend'])) {
        $cacheConfigurations['cache_beautyofcode']['backend'] =
            \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class;
    }
    if (!isset($cacheConfigurations['cache_beautyofcode']['frontend'])) {
        $cacheConfigurations['cache_beautyofcode']['frontend'] =
            \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class;
    }

    // Dynamic changing of t3editor format
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1491758504] = [
        'nodeName' => 't3editor',
        'priority' => '70',
        'class' => \FelixNagel\Beautyofcode\Form\Element\T3editorElement::class,
    ];
}, 'beautyofcode');

<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function ($packageKey) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'TYPO3.'.$packageKey,
        'ContentRenderer',
        array(
            'Content' => 'render',
        ),
        array(),
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] =
        'EXT:beautyofcode/Classes/Hooks/PageLayoutViewHooks.php:TYPO3\Beautyofcode\Hooks\PageLayoutViewHooks';

    // Cache registration
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_beautyofcode'] = array();
    }
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_beautyofcode']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_beautyofcode']['backend'] =
            'TYPO3\\CMS\\Core\\Cache\\Backend\\TransientMemoryBackend';
    }
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_beautyofcode']['frontend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_beautyofcode']['frontend'] =
            'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
    }

    // Dynamic changing of t3editor format for TYPO3 7.x
    if (version_compare(TYPO3_branch, '7.4', '>=')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\T3editor\\Form\\Element\\T3editorElement'] = array(
            'className' => 'TYPO3\\Beautyofcode\\Form\\Element\\T3editorElement',
        );
    }
}, $_EXTKEY);


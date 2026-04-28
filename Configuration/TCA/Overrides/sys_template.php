<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function () {
    $packageKey = 'beautyofcode';

    ExtensionManagementUtility::addStaticFile(
        $packageKey,
        'Configuration/TypoScript/SyntaxHighlighter/',
        'Beauty of Code: SyntaxHighlighter (deprecated)'
    );
    ExtensionManagementUtility::addStaticFile(
        $packageKey,
        'Configuration/TypoScript/Prism/',
        'Beauty of Code: Prism (recommended)'
    );
});

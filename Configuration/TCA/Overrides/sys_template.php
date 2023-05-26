<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function () {
    $packageKey = 'beautyofcode';

    ExtensionManagementUtility::addStaticFile(
        $packageKey,
        'Configuration/TypoScript/SyntaxHighlighter/',
        'beautyOfCode (SyntaxHighlighter)'
    );
    ExtensionManagementUtility::addStaticFile(
        $packageKey,
        'Configuration/TypoScript/Prism/',
        'beautyOfCode (Prism)'
    );
});

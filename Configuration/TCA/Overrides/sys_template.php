<?php

defined('TYPO3') || die();

call_user_func(function () {
    $packageKey = 'beautyofcode';

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
});

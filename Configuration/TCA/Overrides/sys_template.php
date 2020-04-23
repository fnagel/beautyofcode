<?php

defined('TYPO3_MODE') or die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'beautyofcode',
        'Configuration/TypoScript/SyntaxHighlighter/',
        'beautyOfCode (SyntaxHighlighter)'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'beautyofcode',
        'Configuration/TypoScript/Prism/',
        'beautyOfCode (Prism)'
    );
});

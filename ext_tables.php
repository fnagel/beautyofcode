<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function ($packageKey) {
    // Needed to prevent dummy table to be processed when deleting a page in BE
    // See https://github.com/fnagel/beautyofcode/issues/14
    if (TYPO3_MODE !== 'BE') {
        // This dummy TCA is necessary to allow the Extbase data mapper to work
        $GLOBALS['TCA']['tx_beautyofcode_domain_model_flexform'] = [
            'ctrl' => [
                'hideTable' => 1,
                'is_static' => 1,
                'readOnly' => 1,
            ],
            'columns' => [
                'c_label' => ['config' => []],
                'c_lang' => ['config' => []],
                'c_highlight' => ['config' => []],
                'c_collapse' => ['config' => []],
                'c_gutter' => ['config' => []],
                'uid' => ['config' => []],
                'pid' => ['config' => []],
            ],
        ];
    }
}, $_EXTKEY);

<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function ($packageKey) {
    // Needed to prevent dummy table to be processed when deleting a page in BE
    // See https://github.com/fnagel/beautyofcode/issues/14
    if (TYPO3_MODE !== 'BE') {
        // This dummy TCA is necessary to allow the Extbase data mapper to work
        $GLOBALS['TCA']['tx_beautyofcode_domain_model_flexform'] = array(
            'ctrl' => array(
                'hideTable' => 1,
                'is_static' => 1,
                'readOnly' => 1,
            ),
            'columns' => array(
                'c_label' => array('config' => array()),
                'c_lang' => array('config' => array()),
                'c_highlight' => array('config' => array()),
                'c_collapse' => array('config' => array()),
                'c_gutter' => array('config' => array()),
                'uid' => array('config' => array()),
                'pid' => array('config' => array()),
            ),
        );
    }
}, $_EXTKEY);

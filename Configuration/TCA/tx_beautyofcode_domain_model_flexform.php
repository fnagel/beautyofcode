<?php

// Needed to prevent dummy table to be processed when deleting a page in BE
// See https://github.com/fnagel/beautyofcode/issues/14
if (TYPO3_MODE !== 'BE') {
// This dummy TCA is necessary to allow the Extbase data mapper to work
    return [
        'ctrl' => [
            'hideTable' => 1,
            'is_static' => 1,
            'readOnly' => 1,
        ],
        'columns' => [
            'c_label' => [],
            'c_lang' => [],
            'c_file' => [],
            'c_highlight' => [],
            'c_collapse' => [],
            'c_gutter' => [],
            'uid' => [],
            'pid' => [],
        ],
    ];
}

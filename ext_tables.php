<?php
defined('TYPO3') || die();

if (TYPO3_MODE === 'BE') {
    // Add icons to registry
    /* @var $iconRegistry \TYPO3\CMS\Core\Imaging\IconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    $iconRegistry->registerIcon(
        'extensions-beautyofcode',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:beautyofcode/Resources/Public/Icons/Extension.svg']
    );
}

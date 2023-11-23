<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

defined('TYPO3') || die();

// Add icons to registry
$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon(
    'extensions-beautyofcode',
    SvgIconProvider::class,
    ['source' => 'EXT:beautyofcode/Resources/Public/Icons/Extension.svg']
);

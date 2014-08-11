<?php
namespace TYPO3\Beautyofcode\Tests\Unit;

// override from CLI
if (getenv('TYPO3_PATH_WEB')) {
	$typo3CmsComposerPackagePath = getenv('TYPO3_PATH_WEB');
// intelligent guess, probably extension development context
} else {
	$typo3CmsComposerPackagePath = realpath(__DIR__ . '/../../vendor/typo3/cms');
}

putenv('TYPO3_PATH_WEB=' . $typo3CmsComposerPackagePath);

if (!file_exists($typo3CmsComposerPackagePath . '/typo3temp/locks')) {
	mkdir($typo3CmsComposerPackagePath . '/typo3temp/locks', 0777, TRUE);
}

require_once $typo3CmsComposerPackagePath . '/typo3/sysext/core/Build/UnitTestsBootstrap.php';
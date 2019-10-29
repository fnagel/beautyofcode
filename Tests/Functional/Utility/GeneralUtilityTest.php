<?php

namespace FelixNagel\Beautyofcode\Tests\Functional\Utility;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Tests the general utility class.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 *
 * @link http://www.van-tomas.de/
 */
class GeneralUtilityTest extends \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase
{
    /**
     * @var array
     */
    protected $coreExtensionsToLoad = ['t3editor'];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/beautyofcode'];

    /**
     * @test
     */
    public function prefixingWithExtReturnsPathSiteAbsolutePathToExtensionFile()
    {
        $path = \FelixNagel\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath(
            'EXT:beautyofcode/ext_emconf.php'
        );

        $this->assertStringStartsWith('typo3conf/', $path);
    }

    /**
     * @test
     */
    public function prefixingWithFileReturnsPathSiteAbsolutePathToFile()
    {
        define('TYPO3_OS', !stristr(PHP_OS, 'darwin') && stristr(PHP_OS, 'win') ? 'WIN' : '');
        $path = \FelixNagel\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('FILE:fileadmin/test.js');

        $this->assertStringStartsWith('fileadmin/', $path);
    }

    /**
     * @test
     */
    public function passingInAnExternalUrlWillReturnItUntouched()
    {
        $externalPath = 'http://www.example.org/test.js';

        $path = \FelixNagel\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($externalPath);

        $this->assertEquals($externalPath, $path);
    }

    /**
     * @test
     */
    public function passingInCombinedFileAndExtNotationWillReturnPathSiteAbsolutePathToExtensionFile()
    {
        $path = \FelixNagel\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('FILE:EXT:beautyofcode/ext_localconf.php');

        $this->assertStringStartsWith('typo3conf/', $path);
    }

    /**
     * @test
     */
    public function passingInACompletelyInvalidPathLeavesItUntouched()
    {
        $invalidPath = 'foo://bar.jpeg';

        $path = \FelixNagel\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($invalidPath);

        $this->assertEquals($invalidPath, $path);
    }

    /**
     * @test
     */
    public function passingFileNotationWithExternalUrlWillReturnAnEmptyString()
    {
        $invalidExternalPath = 'FILE:http://example.org/test.js';

        $path = \FelixNagel\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($invalidExternalPath);

        $this->assertEquals('', $path);
    }
}

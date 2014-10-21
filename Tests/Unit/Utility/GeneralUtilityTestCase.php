<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Utility;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests the general utility class
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Utility
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class GeneralUtilityTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	public function setUp() {
		$this->createPackageManagerMock();
	}

	public function testPrefixingWithExtReturnsPathSiteAbsolutePathToExtensionFile() {
		$this->packageManagerMock
			->expects($this->any())
			->method('isPackageActive')
			->with($this->equalTo('beautyofcode'))
			->will($this->returnValue(TRUE));

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('EXT:beautyofcode/ext_emconf.php');

		$this->assertStringStartsWith('typo3conf/', $path);
	}

	public function testPrefixingWithFileReturnsPathSiteAbsolutePathToFile() {
		define('TYPO3_OS', !stristr(PHP_OS, 'darwin') && stristr(PHP_OS, 'win') ? 'WIN' : '');
		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('FILE:fileadmin/test.js');

		$this->assertStringStartsWith('fileadmin/', $path);
	}

	public function testPassingInAnExternalUrlWillReturnItUntouched() {
		$externalPath = 'http://www.example.org/test.js';

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($externalPath);

		$this->assertEquals($externalPath, $path);
	}

	public function testPassingInCombinedFileAndExtNotationWillReturnPathSiteAbsolutePathToExtensionFile() {
		$this->packageManagerMock
			->expects($this->any())
			->method('isPackageActive')
			->will($this->returnValue(TRUE));

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('FILE:EXT:beautyofcode/ext_localconf.php');

		$this->assertStringStartsWith('typo3conf/', $path);
	}

	public function testPassingInACompletelyInvalidPathLeavesItUntouched() {
		$invalidPath = 'foo://bar.jpeg';

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($invalidPath);

		$this->assertEquals($invalidPath, $path);
	}

	public function testPassingFileNotationWithExternalUrlWillReturnAnEmptyString() {
		$invalidExternalPath = 'FILE:http://example.org/test.js';

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($invalidExternalPath);

		$this->assertEquals('', $path);
	}
}
?>
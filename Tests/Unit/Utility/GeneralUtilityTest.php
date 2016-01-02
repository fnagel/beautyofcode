<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Utility;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 ...
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

use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Tests the general utility class
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Utility
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class GeneralUtilityTest extends UnitTestCase {

	protected $backupGlobalsBlacklist = array('TYPO3_CONF_VARS', 'TYPO3_LOADED_EXT');

	public function setUp() {
		if (FALSE === defined('PATH_site')) {
			define('PATH_site', '/home/foo/');
		}

		if (FALSE === defined('REQUIRED_EXTENSIONS')) {
			define('REQUIRED_EXTENSIONS', 'foo,bar');
		}

		if (FALSE === defined('PATH_typo3conf')) {
			define('PATH_typo3conf', '/home/foo/typo3conf/');
		}

		if (FALSE === defined('PATH_typo3')) {
			define('PATH_typo3', '/home/foo/typo3/');
		}

		/* @var $packageManagerMock PackageManager|\PHPUnit_Framework_MockObject_MockObject */
		$packageManagerMock = $this->getMock(PackageManager::class);

		ExtensionManagementUtility::setPackageManager($packageManagerMock);

		$packageMock = $this->getMockBuilder(Package::class)
			->disableOriginalConstructor()
			->getMock();

		$packageMock
			->expects($this->any())
			->method('getPackagePath')
			->will($this->returnValue('/home/foo/typo3conf/ext/beautyofcode'));

		$packageManagerMock
			->expects($this->any())
			->method('isPackageActive')
			->with($this->equalTo('beautyofcode'))
			->will($this->returnValue(TRUE));

		$packageManagerMock
			->expects($this->any())
			->method('getPackage')
			->with($this->equalTo('beautyofcode'))
			->will($this->returnValue($packageMock));

		$GLOBALS['TYPO3_CONF_VARS'] = array(
			'EXT' => array(
				'extListArray' => array(
					'beautyofcode'
				),
				'requiredExt' => array('foo', 'bar'),
			),
		);

		$GLOBALS['TYPO3_LOADED_EXT']['beautyofcode'] = array(
			'siteRelPath' => 'typo3conf/ext/beautyofcode/'
		);
	}

	/**
	 *
	 * @test
	 */
	public function prefixingWithExtReturnsPathSiteAbsolutePathToExtensionFile() {
		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath(
			'EXT:beautyofcode/ext_emconf.php'
		);

		$this->assertStringStartsWith('typo3conf/', $path);
	}

	/**
	 *
	 * @test
	 */
	public function prefixingWithFileReturnsPathSiteAbsolutePathToFile() {
		define('TYPO3_OS', !stristr(PHP_OS, 'darwin') && stristr(PHP_OS, 'win') ? 'WIN' : '');
		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('FILE:fileadmin/test.js');

		$this->assertStringStartsWith('fileadmin/', $path);
	}

	/**
	 *
	 * @test
	 */
	public function passingInAnExternalUrlWillReturnItUntouched() {
		$externalPath = 'http://www.example.org/test.js';

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($externalPath);

		$this->assertEquals($externalPath, $path);
	}

	/**
	 *
	 * @test
	 */
	public function passingInCombinedFileAndExtNotationWillReturnPathSiteAbsolutePathToExtensionFile() {
		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('FILE:EXT:beautyofcode/ext_localconf.php');

		$this->assertStringStartsWith('typo3conf/', $path);
	}

	/**
	 *
	 * @test
	 */
	public function passingInACompletelyInvalidPathLeavesItUntouched() {
		$invalidPath = 'foo://bar.jpeg';

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($invalidPath);

		$this->assertEquals($invalidPath, $path);
	}

	/**
	 *
	 * @test
	 */
	public function passingFileNotationWithExternalUrlWillReturnAnEmptyString() {
		$invalidExternalPath = 'FILE:http://example.org/test.js';

		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($invalidExternalPath);

		$this->assertEquals('', $path);
	}
}

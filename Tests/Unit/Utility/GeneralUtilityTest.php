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

/**
 * Tests the general utility class
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Utility
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class GeneralUtilityTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	protected $backupGlobalsBlacklist = array('TYPO3_CONF_VARS', 'TYPO3_LOADED_EXT');

	public function setUp() {
		if (FALSE === defined('PATH_site')) {
			define('PATH_site', '/home/foo/');
		}

		if (FALSE === defined('REQUIRED_EXTENSIONS')) {
			define('REQUIRED_EXTENSIONS', 'foo,bar');
		}

		$GLOBALS['TYPO3_CONF_VARS']['EXT']['extListArray'] = array('beautyofcode');
		$GLOBALS['TYPO3_CONF_VARS']['EXT']['requiredExt'] = array('foo', 'bar');

		$GLOBALS['TYPO3_LOADED_EXT']['beautyofcode'] = array(
			'siteRelPath' => 'typo3conf/ext/beautyofcode/'
		);
	}

	/**
	 *
	 * @test
	 */
	public function prefixingWithExtReturnsPathSiteAbsolutePathToExtensionFile() {
		$path = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath('EXT:beautyofcode/ext_emconf.php');

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
}
?>
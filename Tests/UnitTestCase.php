<?php
namespace TYPO3\Beautyofcode\Tests;

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

use \org\bovigo\vfs\vfsStream;
use \org\bovigo\vfs\vfsStreamWrapper;

/**
 * UnitTestCase
 *
 * @package \TYPO3\Beautyofcode\Tests
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
abstract class UnitTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\CMS\Core\Package\PackageManager
	 */
	protected $packageManagerMock;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\CMS\Core\Database\DatabaseConnection]
	 */
	protected $dbMock;

	/**
	 * createPackageManagerMock
	 *
	 * @return void
	 */
	protected function createPackageManagerMock() {
		$this->packageManagerMock = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Package\\UnitTestPackageManager')
			->getMock();

		$this->packageManagerMock
			->expects($this->any())
			->method('getPackage')
			->will(
				$this->returnCallback(
					array($this, 'getValidPackage')
				)
			);

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::setPackageManager(
			$this->packageManagerMock
		);
	}

	/**
	 * Return a proper package mock
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	public function getValidPackage() {
		$arguments = func_get_args();
		$packageKey = $arguments[0];

		$packageMock = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Package\\Package')
			->disableOriginalConstructor()
			->getMock();
		$packageMock
			->expects($this->any())
			->method('getPackagePath')
			->will($this->returnValue(PATH_site . 'typo3conf/ext/' . $packageKey . '/'));

		return $packageMock;
	}

	/**
	 * createDatabaseConnectionMock
	 *
	 * Tricks the Cache DB backend and other stuff which relies on $GLOBALS['TYPO3_DB'].
	 *
	 * @return void
	 */
	public function createDatabaseConnectionMock() {
		$this->dbMock = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Database\\DatabaseConnection')
			->getMock();

		$GLOBALS['TYPO3_DB'] = $this->dbMock;
	}

	/**
	 * Helper method to create test directory.
	 *
	 * @param string $rootDirectoryName
	 * @param array $structure
	 * @return string A unique directory name prefixed with test_.
	 */
	protected function createTestDirectory($rootDirectoryName = 'test', array $structure = array()) {
		if (!class_exists('org\\bovigo\\vfs\\vfsStreamWrapper')) {
			$this->markTestSkipped('getFilesInDirCreateTestDirectory() helper method not available without vfsStream.');
		}

		vfsStream::setup($rootDirectoryName, NULL, $structure);
		$vfsUrl = vfsStream::url($rootDirectoryName);

		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			// set random values for mtime
			foreach ($structure as $structureLevel1Key => $structureLevel1Content) {
				$newModificationTime = rand();
				if (is_array($structureLevel1Content)) {
					foreach ($structureLevel1Content as $structureLevel2Key => $_) {
						touch($vfsUrl . '/' . $structureLevel1Key . '/' . $structureLevel2Key, $newModificationTime);
					}
				} else {
					touch($vfsUrl . '/' . $structureLevel1Key, $newModificationTime);
				}
			}
		}

		return $vfsUrl;
	}
}
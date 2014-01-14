<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Configuration\Brush;

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
 * The Standalone brush discovery test suite
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Configuration\Brush
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class StandaloneBrushTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	protected $backupGlobalsBlacklist = array('TYPO3_CONF_VARS', 'TYPO3_LOADED_EXT');

	public function setUp() {
		if (FALSE === defined('PATH_site')) {
			define('PATH_site', realpath(dirname(__FILE__) . '/../../../../'));
		}
		if (FALSE === defined('REQUIRED_EXTENSIONS')) {
			define('REQUIRED_EXTENSIONS', '');
		}

		$GLOBALS['TYPO3_CONF_VARS'] = array(
			'EXT' => array(
				'extListArray' => array('beautyofcode'),
				'requiredExt' => array(),
			),
		);

		$GLOBALS['TYPO3_LOADED_EXT'] = array(
			'beautyofcode' => array(
				'siteRelPath' => '/',
			)
		);
	}

	/**
	 *
	 * @test
	 */
	public function autoloaderFileIsNotInBrushesList() {
		$sut = new \TYPO3\Beautyofcode\Configuration\Brush\StandaloneBrush();
		$brushes = $sut->getBrushes();

		$this->assertNotEmpty($brushes);
		$this->assertArrayNotHasKey('Autoloader', $brushes);
		$this->assertArrayNotHasKey('Autoloader.js', $brushes);
		$this->assertArrayNotHasKey('shAutoloader', $brushes);
		$this->assertArrayNotHasKey('shAutoloader.js', $brushes);
	}

	/**
	 *
	 * @test
	 */
	public function applescripttBrushIsInList() {
		$sut = new \TYPO3\Beautyofcode\Configuration\Brush\StandaloneBrush();
		$brushes = $sut->getBrushes();

		$this->assertArrayHasKey('AppleScript', $brushes);
	}

	/**
	 *
	 * @test
	 */
	public function sassBrushIsInList() {
		$sut = new \TYPO3\Beautyofcode\Configuration\Brush\StandaloneBrush();
		$brushes = $sut->getBrushes();

		$this->assertArrayHasKey('Sass', $brushes);
	}
}
?>
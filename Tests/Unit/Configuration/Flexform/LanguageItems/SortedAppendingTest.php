<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Configuration\Flexform\LanguageItems;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests the sorted appending of configured brushes to the list of flexform items
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class SortedAppendingTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	protected $backupGlobalsBlacklist = array('TYPO3_CONF_VARS', 'TYPO3_LOADED_EXT');

	public function setUp() {
		if (FALSE === defined('PATH_site')) {
			define('PATH_site', realpath(dirname(__FILE__) . '/../../../../../'));
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
	public function configuredBrushesAreAppendedSortedToTheReturnValue() {
		/* @var $pageRepositoryMock \TYPO3\CMS\Frontend\Page\PageRepository */
		$pageRepositoryMock = $this->getMock('TYPO3\\CMS\\Frontend\\Page\\PageRepository');

		/* @var $templateServiceMock \TYPO3\CMS\Core\TypoScript\TemplateService */
		$templateServiceMock = $this->getMock('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');

		$sut = new \TYPO3\Beautyofcode\Configuration\Flexform\LanguageItems();
		$sut->injectPageRepository($pageRepositoryMock);
		$sut->injectTemplateService($templateServiceMock);

		$templateServiceMock->setup = array(
			'plugin.' => array(
				'tx_beautyofcode.' => array(
					'settings.' => array(
						'library' => 'Jquery'
					)
				)
			)
		);

		$configFromFlexform = array(
			'row' => array(
				'uid' => 1,
				'pid' => 1,
			),
			'items' => array(
				array(
					'Plain', // TCEforms: label
					'plain' // TCEforms: key
				),
			),
		);

		$newConfig = $sut->getDiscovereBrushes($configFromFlexform);

		$this->assertEquals('plain', $newConfig['items'][0][1]);
		$this->assertEquals('AS3', $newConfig['items'][1][1]);
		$this->assertEquals('Bash', $newConfig['items'][2][1]);
		$this->assertEquals('Cpp', $newConfig['items'][3][1]);
	}
}
?>
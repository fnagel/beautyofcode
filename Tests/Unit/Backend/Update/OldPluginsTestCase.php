<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Backend\Update;

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
 * Tests the OldPlugins updater
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Backend\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class OldPluginsTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	public function setUp() {
		$this->createPackageManagerMock();

		$this->db = $this->getMockBuilder('TYPO3\\CMS\\Core\\Database\\DatabaseConnection')
			->getMock();
	}

	protected function assertOldPluginInstancesExist() {
		$this->db
			->expects($this->once())
			->method('exec_SELECTcountRows')
			->with(
				$this->equalTo('*'),
				$this->equalTo('tt_content'),
				$this->equalTo('list_type = "beautyofcode_pi1"')
			)
			->will($this->returnValue(1));
	}

	public function testTheListTypeFieldOfOldPluginContentElementsWillBeUpdated() {
		$_POST = array(
			'update' => array(
				'oldPlugins' => '1',
			),
		);

		$this->assertOldPluginInstancesExist();

		$this->db
			->expects($this->once())
			->method('exec_UPDATEquery')
			->with(
				$this->equalTo('tt_content'),
				$this->equalTo('list_type = "beautyofcode_pi1"'),
				$this->equalTo(array('list_type' => 'beautyofcode_contentrenderer'))
			);

		$view = $this->getMockBuilder('TYPO3\\CMS\\Fluid\\View\\StandaloneView')
			->disableOriginalConstructor()
			->getMock();

		$view->expects($this->at(2))
			->method('assign')
			->with(
				$this->equalTo('countOldPlugins'),
				$this->equalTo(1)
			);

		$sut = new \TYPO3\Beautyofcode\Backend\Update\OldPlugins();
		$sut->injectDatabaseConnection($this->db);
		$sut->injectView($view);
		$sut->initializeObject();

		$sut->execute();
	}
}
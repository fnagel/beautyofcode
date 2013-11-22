<?php
namespace TYPO3\Beautyofcode\Tests\Unit;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Class short description
 *
 * Class long description
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ExtUpdateTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	protected $backupGlobalsBlacklist = array('TYPO3_DB');

	/**
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	public function setUp() {
		$this->db = $this->getMockBuilder('TYPO3\\CMS\\Core\\Database\\DatabaseConnection')
			->getMock();

		$GLOBALS['TYPO3_DB'] = $this->db;
	}

	/**
	 *
	 * @test
	 */
	public function accessReturnsTrueIfListTypesWithOldPluginSignatureWereFound() {
		$this->assertOldPluginInstancesExist();

		$sut = new \ext_update();

		$this->assertTrue($sut->access());
	}

	protected function assertOldPluginInstancesExist() {
		$this->db
			->expects($this->at(0))
			->method('exec_SELECTcountRows')
			->with(
				$this->equalTo('*'),
				$this->equalTo('tt_content'),
				$this->equalTo('list_type = "beautyofcode_pi1"')
			)
			->will($this->returnValue(1));
	}

	/**
	 *
	 * @test
	 */
	public function accessReturnsTrueIfTtContentRecordsWithOldFlexformStringsWereFound() {
		$this->assertOldFlexformConfigurationStringsExist();

		$sut = new \ext_update();

		$this->assertTrue($sut->access());
	}

	protected function assertOldFlexformConfigurationStringsExist() {
		$this->db
			->expects($this->at(1))
			->method('exec_SELECTcountRows')
			->with(
				$this->equalTo('*'),
				$this->equalTo('tt_content'),
				$this->stringContains('%<cLabel>%', FALSE)
			)
			->will($this->returnValue(1));
	}

	/**
	 *
	 * @test
	 */
	public function mainWillUpdateTheListTypeFieldOfOldPluginContentElements() {
		$this->assertOldPluginInstancesExist();

		$this->db
			->expects($this->at(1))
			->method('exec_UPDATEquery')
			->with(
				$this->equalTo('tt_content'),
				$this->equalTo('list_type = "beautyofcode_pi1"'),
				$this->equalTo(array('list_type' => 'beautyofcode_contentrenderer'))
			);

		$sut = new \ext_update();

		$updateOutput = $sut->main();

		$this->assertEquals('<p>Updated plugin signature of 1 tt_content records.</p>', $updateOutput);
	}

	/**
	 *
	 * @test
	 */
	public function mainWillUpdateTheFlexformConfigurationStringOfOldFlexformConfigurations() {
		$this->assertOldFlexformConfigurationStringsExist();

		$this->db
			->expects($this->at(2))
			->method('exec_SELECTgetRows')
			->with(
				$this->equalTo('uid, pi_flexform'),
				$this->equalTo('tt_content'),
				$this->stringContains('%<cCode>%', FALSE)
			)
			->will($this->returnValue(array(array('uid' => 1, 'pi_flexform' => '<cCode>php</cCode>'))));

		$this->db
			->expects($this->at(3))
			->method('exec_UPDATEquery')
			->with(
				$this->equalTo('tt_content'),
				$this->equalTo('uid = 1'),
				$this->equalTo(array('pi_flexform' => '<settings.cCode>php</settings.cCode>'))
			)
			->will($this->returnValue(TRUE));

		$sut = new \ext_update();

		$updateOutput = $sut->main();

		$this->assertEquals('<p>Found 1 old flexform configurations. Updated 1 of them.</p>', $updateOutput);
	}
}
?>
<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Update;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Tommy Juhnke <typo3@van-tomas.de>
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
 * Tests the language setting updater
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class LanguageSettingTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	public function setUp() {
		$this->createPackageManagerMock();

		$this->db = $this->getMockBuilder('TYPO3\\CMS\\Core\\Database\\DatabaseConnection')
			->getMock();

		$GLOBALS['TYPO3_DB'] = $this->db;

		$this->setUpConfigurationValuesForFlexformTools();
	}

	/**
	 * FlexformTools need some constants + configuration settings
	 *
	 * @return void
	 */
	protected function setUpConfigurationValuesForFlexformTools() {
		if (!defined('LF')) {
			define('LF', chr(10));
		}

		$GLOBALS['TYPO3_CONF_VARS']['BE']['niceFlexFormXMLtags'] = TRUE;
		$GLOBALS['TYPO3_CONF_VARS']['BE']['compactFlexFormXML'] = FALSE;
	}

	protected function assertAnUpdateablePluginInstanceExist() {
		$updateablePluginInstance = array(
			array(
				'uid' => 1,
				'header' => 'An updateable plugin instance',
				'pi_flexform' => file_get_contents(__DIR__ . '/Fixtures/UpdateablePluginInstanceFlexform.xml'),
			),
		);

		$this->db
			->expects($this->once())
			->method('exec_SELECTquery')
			->with(
				$this->equalTo('uid, header, pi_flexform'),
				$this->equalTo('tt_content'),
				$this->equalTo('list_type IN (\'beautyofcode_pi1\',\'beautyofcode_contentrenderer\')')
			)
			->will($this->returnValue($updateablePluginInstance));

		$this->db
			->expects($this->at(1))
			->method('sql_fetch_assoc')
			->will($this->returnValue($updateablePluginInstance[0]));

		$this->db
			->expects($this->at(2))
			->method('sql_fetch_assoc')
			->will($this->returnValue(FALSE));
	}

	/**
	 *
	 * @test
	 */
	public function theFlexformFieldGetsUpdatedInTheCLangField() {
		$_POST = array(
			'update' => array(
				'language' => '1',
			),
			'language' => array(
				1 => 'TypoScript',
			),
		);

		$this->assertAnUpdateablePluginInstanceExist();

		$this->db
			->expects($this->once())
			->method('exec_UPDATEquery')
			->with(
				$this->equalTo('tt_content'),
				$this->equalTo('uid=1'),
				$this->equalTo(array(
					'pi_flexform' => file_get_contents(__DIR__ . '/Fixtures/UpdateQueryPiFlexform.xml')))
				)
			->will($this->returnValue(TRUE));

		$view = $this->getMockBuilder('TYPO3\\CMS\\Fluid\\View\\StandaloneView')
			->disableOriginalConstructor()
			->getMock();

		$view->expects($this->at(2))
			->method('assign')
			->with(
				$this->equalTo('totalUpdates'),
				$this->equalTo(1)
			);

		$view->expects($this->at(3))
			->method('assign')
			->with(
				$this->equalTo('successfulUpdates'),
				$this->equalTo(1)
			);

		$objectManager = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->disableOriginalConstructor()
			->getMock();

		$configurationManager = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager')
			->getMock();
		$configurationManager
			->expects($this->any())
			->method('getConfiguration')
			->will(
				$this->returnValue(
					array(
						'plugin.' => array(
							'tx_beautyofcode.' => array(
								'settings.' => array(
									'library' => 'Prism',
								)
							),
						),
					)
				)
			);

		$brushDiscoveryService = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService')
			->getMock();

		$flexformTools = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools')
			->getMock();
		$flexformTools->expects($this->once())
			->method('flexArray2Xml')
			->will($this->returnValue(file_get_contents(__DIR__ . '/Fixtures/FlexformToolsFlexArray2XmlReturnValue.xml')));

		$sut = new \TYPO3\Beautyofcode\Update\LanguageSetting();
		$sut->injectDatabaseConnection($this->db);
		$sut->injectView($view);
		$sut->injectObjectManager($objectManager);
		$sut->injectConfigurationManager($configurationManager);
		$sut->injectBrushDiscoveryService($brushDiscoveryService);
		$sut->injectFlexformTools($flexformTools);

		$sut->initializeObject();

		$sut->execute();
	}
}
?>
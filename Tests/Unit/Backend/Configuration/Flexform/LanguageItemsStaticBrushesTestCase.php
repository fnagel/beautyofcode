<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Backend\Configuration\Flexform;

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
 * Tests for static brushes in LanguageItems postProc
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class LanguageItemsStaticBrushesTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManagerMock;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\BrushDiscovery
	 */
	protected $brushDiscovery;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\Configuration\Prism
	 */
	protected $highlighterConfigurationMock;

	/**
	 *
	 * @var \TYPO3\CMS\Backend\Form\FormEngine
	 */
	protected $formEngineMock;

	/**
	 *
	 * @var array
	 */
	protected $flexformConfigurationFixture = array(
		'row' => array(
			'uid' => 1,
			'pid' => 1,
		),
		'items' => array(
			array(
				'This item is not visible', // TCEforms: label
				'' // TCEforms: key
			),
		),
	);

	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp() {
		$this->objectManagerMock = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->disableOriginalConstructor()
			->getMock();

		$this->brushDiscovery = $this->getMock(
			'TYPO3\\Beautyofcode\\Highlighter\\BrushDiscovery'
		);

		$this->highlighterConfigurationMock = $this->getMock(
			'TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface'
		);

		$this->formEngineMock = $this
			->getMockBuilder('TYPO3\\CMS\\Backend\\Form\\FormEngine')
			->disableOriginalConstructor()
			->getMock();
	}

	public function assertConfiguredPrism() {
		$this->brushDiscovery
			->expects($this->once())
			->method('getBrushes')
			->with($this->highlighterConfigurationMock)
			->will($this->returnValue(
				array(
					'plain' => 'Plain',
					'php' => 'PHP',
					'sql' => 'SQL / MySQL',
				)
			));

		$brushAliasIdentifierMap = array(
			array('plain', 'plain'),
			array('php', 'php'),
			array('sql', 'sql'),
		);

		$this
			->highlighterConfigurationMock
			->method('getBrushAliasByIdentifier')
			->will($this->returnValueMap($brushAliasIdentifierMap));
	}

	public function testStaticBrushesOverrideTheReturnValue() {
		$this->assertConfiguredPrism();

		$sut = new \TYPO3\Beautyofcode\Backend\Configuration\Flexform\LanguageItems();
		$sut->injectObjectManager($this->objectManagerMock);
		$sut->injectBrushDiscovery($this->brushDiscovery);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->initializeObject();

		$newConfig = $sut->getDiscoveredBrushes(
			$this->flexformConfigurationFixture,
			$this->formEngineMock
		);

		$this->assertEquals('plain', $newConfig['items'][0][1]);
		$this->assertEquals('php', $newConfig['items'][1][1]);
		$this->assertEquals('sql', $newConfig['items'][2][1]);
	}
}
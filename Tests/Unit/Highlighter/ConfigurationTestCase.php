<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Highlighter;

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
 * ConfigurationTestCase
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class ConfigurationTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\Configuration
	 */
	protected $sut;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\CMS\Extbase\Object\ObjectManager]
	 */
	protected $objectManagerMock;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\CMS\Extbase\Configuration\ConfigurationManager]
	 */
	protected $configurationManagerMock;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\Beautyofcode\Highlighter\ConfigurationInterface]
	 */
	protected $concreteConfigurationMock;

	public function setUp() {
		$this->objectManagerMock = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->disableOriginalConstructor()
			->getMock();

		$this->configurationManagerMock = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager')
			->getMock();

		$this->sut = new \TYPO3\Beautyofcode\Highlighter\Configuration();

		$this->concreteConfigurationMock = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface')
			->getMock();
	}

	/**
	 *
	 * @test
	 */
	public function dependencyInjectionAndTypoScriptConfigurationGiveAConcreteHighlighterConfiguration() {
		// after dependency injection...
		$typoscriptConfiguration = array(
			'plugin.' => array(
				'tx_beautyofcode.' => array(
					'settings.' => array(
						'library' => 'Prism',
					),
				),
			),
		);

		$this->configurationManagerMock
			->expects($this->once())
			->method('getConfiguration')
			->will($this->returnValue($typoscriptConfiguration));

		$this->sut->injectObjectManager($this->objectManagerMock);
		$this->sut->injectConfiguration($this->configurationManagerMock);

		// ...initializing will set the concrete configuratin implementation...

		$this->sut->injectObjectManager($this->objectManagerMock);

		$this->objectManagerMock
			->expects($this->once())
			->method('get')
			->with($this->equalTo('TYPO3\\Beautyofcode\\Highlighter\\Configuration\\Prism'))
			->will($this->returnValue($this->concreteConfigurationMock));

		$this->sut->initializeObject();

		// ...and each interface method will delegate to concrete implementation
		$this->concreteConfigurationMock
			->expects($this->at(0))
			->method('getFailSafeBrushAlias')
			->with('markup');

		$this->sut->getFailSafeBrushAlias('markup');

		$this->concreteConfigurationMock
			->expects($this->at(0))
			->method('prepareRegisteredBrushes')
			->with(array());

		$this->sut->prepareRegisteredBrushes(array());

		$this->concreteConfigurationMock
			->expects($this->at(0))
			->method('getClassAttributeString');

		$flexform = $this->getMock('TYPO3\\Beautyofcode\\Domain\\Model\\Flexform');

		$this->sut->getClassAttributeString($flexform);

		$this->concreteConfigurationMock
			->expects($this->at(0))
			->method('getBrushAliasByIdentifier')
			->with('Bash');

		$this->sut->getBrushAliasByIdentifier('Bash');
	}
}
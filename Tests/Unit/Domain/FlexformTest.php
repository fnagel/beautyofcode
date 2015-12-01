<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Domain;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
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

use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Tests the flexform domain object
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Domain
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class FlexformTest extends UnitTestCase {

	/**
	 * ConfigurationInterface
	 *
	 * @var ConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $highlighterConfigurationMock;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Domain\Model\Flexform
	 */
	protected $sut;

	public function setUp() {
		$this->highlighterConfigurationMock = $this->getMock(ConfigurationInterface::class);

		$this->sut = new \TYPO3\Beautyofcode\Domain\Model\Flexform();

		$this->sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);

		$this->sut->setCLabel('The label');
		$this->sut->setCLang('typoscript');
		$this->sut->setCHighlight('1,2-3,8');
		$this->sut->setCCollapse('1');
		$this->sut->setCGutter('1');
	}

	/**
	 *
	 * @test
	 */
	public function settingAnEmptyValueForSyntaxHighlighterWillSkipTheOutputForTheSetting() {
		$this->highlighterConfigurationMock->expects($this->once())->method('getClassAttributeString')->will($this->returnValue(''));

		$this->sut->setCCollapse('');

		$this->assertNotContains('collapse', $this->sut->getClassAttributeString());
	}

	/**
	 *
	 * @test
	 */
	public function settingAutoValueForSyntaxHighlighterWillSkipTheOutputForTheSetting() {
		$this->sut->setCGutter('auto');

		$this->assertNotContains('gutter', $this->sut->getClassAttributeString());
	}

	/**
	 *
	 * @test
	 */
	public function highlightSettingHasSpecialFormattingForSyntaxHighlighter() {
		$this->assertContains('highlight: [', $this->sut->getClassAttributeString());
	}

	/**
	 *
	 * @test
	 */
	public function highlightSettingWilllBeExpandedForSyntaxHighlighter() {
		$this->assertContains('highlight: [1,2,3,8]', $this->sut->getClassAttributeString());
	}

	/**
	 *
	 * @test
	 */
	public function plainBrushIsAlwaysAvailableInAutoloaderBrushesStackForSyntaxHighlighter() {
		$this->highlighterConfigurationMock
			->expects($this->once())->method('getAutoloaderBrushMap')
			->will($this->returnValue(array('plain' => 'Plain')));

		$brushes = $this->sut->getAutoloaderBrushMap();

		$this->assertArrayHasKey('plain', $brushes);
	}

	/**
	 *
	 * @test
	 */
	public function brushesForSyntaxHighlighterAreMappedToASuitableCssTagString() {
		$this->highlighterConfigurationMock
			->expects($this->once())->method('getAutoloaderBrushMap')
			->will($this->returnValue(array('typoscript' => 'Typoscript', 'actionscript3' => 'AS3')));

		$brushes = $this->sut->getAutoloaderBrushMap();

		$this->assertArrayHasKey('typoscript', $brushes);
		$this->assertArrayHasKey('actionscript3', $brushes);
	}

	/**
	 *
	 * @test
	 */
	public function getIsGutterActiveReturnsFalseIfInstanceIsSetToZero() {
		$this->sut->setCGutter('0');

		$this->assertFalse($this->sut->getIsGutterActive());
	}

	/**
	 *
	 * @test
	 */
	public function getIsGutterActiveReturnsTrueIfInstanceIsSetToOne() {
		$this->sut->setCGutter('1');

		$this->assertTrue($this->sut->getIsGutterActive());
	}

	/**
	 *
	 * @test
	 */
	public function getIsGutterActiveReturnsFalseIfInstanceIsSetToAutoAndDefaultValueIsFalsy() {
		$this->sut->setCGutter('auto');
		$this->sut->setTyposcriptDefaults(array('gutter' => ''));

		$this->assertFalse($this->sut->getIsGutterActive());
	}

	/**
	 *
	 * @test
	 */
	public function getIsGutterActiveReturnsFalseIfInstanceIsSetToAutoAndDefaultValueIsOff() {
		$this->sut->setCGutter('auto');
		$this->sut->setTyposcriptDefaults(array('gutter' => '0'));

		$this->assertFalse($this->sut->getIsGutterActive());
	}

	/**
	 *
	 * @test
	 */
	public function getIsGutterActiveReturnsTrueIfInstanceIsSetToAutoAndDefaultValueIsOn() {
		$this->sut->setCGutter('auto');
		$this->sut->setTyposcriptDefaults(array('gutter' => '1'));

		$this->assertTrue($this->sut->getIsGutterActive());
	}
}

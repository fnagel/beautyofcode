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

/**
 * Tests the flexform domain object
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Domain
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class FlexformTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Domain\Model\Flexform
	 */
	protected $sut;

	public function setUp() {
		$this->sut = new \TYPO3\Beautyofcode\Domain\Model\Flexform();
		$this->sut->setCLabel('The label');
		$this->sut->setCLang('typoscript');
		$this->sut->setCCode('page = PAGE\npage.10 = TEXT\npage.10.value = Hello World!');
		$this->sut->setCHighlight('1,2-3,8');
		$this->sut->setCCollapse('1');
		$this->sut->setCGutter('1');
	}

	public function testGetIsGutterActiveReturnsFalseIfInstanceIsSetToZero() {
		$this->sut->setCGutter('0');

		$this->assertFalse($this->sut->getIsGutterActive());
	}

	public function testGetIsGutterActiveReturnsTrueIfInstanceIsSetToOne() {
		$this->sut->setCGutter('1');

		$this->assertTrue($this->sut->getIsGutterActive());
	}

	public function testGetIsGutterActiveReturnsFalseIfInstanceIsSetToAutoAndDefaultValueIsFalsy() {
		$this->sut->setCGutter('auto');
		$this->sut->setTyposcriptDefaults(array('gutter' => ''));

		$this->assertFalse($this->sut->getIsGutterActive());
	}

	public function testGetIsGutterActiveReturnsFalseIfInstanceIsSetToAutoAndDefaultValueIsOff() {
		$this->sut->setCGutter('auto');
		$this->sut->setTyposcriptDefaults(array('gutter' => '0'));

		$this->assertFalse($this->sut->getIsGutterActive());
	}

	public function testGetIsGutterActiveReturnsTrueIfInstanceIsSetToAutoAndDefaultValueIsOn() {
		$this->sut->setCGutter('auto');
		$this->sut->setTyposcriptDefaults(array('gutter' => '1'));

		$this->assertTrue($this->sut->getIsGutterActive());
	}
}
?>
<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Highlighter\Configuration;

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
 * PrismTestCase
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Highlighter\Configuration
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class PrismTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\Configuration\Prism
	 */
	protected $sut;

	public function setUp() {
		$this->sut = new \TYPO3\Beautyofcode\Highlighter\Configuration\Prism(array(), array());
	}

	public function testLineHighlightingIsAvailable() {
		$flexformMock = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Domain\\Model\\Flexform')
			->getMock();

		$flexformMock
			->expects($this->once())
			->method('getCHighlight')
			->will($this->returnValue('1,3-5,11'));

		$classAttributeString = $this->sut->getClassAttributeString($flexformMock);

		$this->assertContains('data-line', $classAttributeString);
		$this->assertContains('data-line="1,3,4,5,11"', $classAttributeString);
	}

	public function testAutoValueDisablesLineHighlighting() {
		$flexformMock = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Domain\\Model\\Flexform')
			->getMock();

		$flexformMock
			->expects($this->once())
			->method('getCHighlight')
			->will($this->returnValue('auto'));

		$classAttributeString = $this->sut->getClassAttributeString($flexformMock);

		$this->assertNotContains('data-line', $classAttributeString);
	}
}
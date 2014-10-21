<?php
namespace TYPO3\Beautyofcode\Tests\Unit\ViewHelpers\Backend\PageLayoutView;

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
 * CalculateTextareaHeightViewHelperTestCase
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\ViewHelpers\Backend\PageLayoutView
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class CalculateTextareaHeightViewHelperTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\ViewHelpers\Backend\PageLayoutView\CalculateTextareaHeightViewHelper
	 */
	protected $sut;

	public function setUp() {
		$this->sut = new \TYPO3\Beautyofcode\ViewHelpers\Backend\PageLayoutView\CalculateTextareaHeightViewHelper();
	}

	public function testPassingInSmallContentWillMultiplyNumberOfLinesWithGivenSmallTextareaFactorAndAddAdditionalSpace() {
		// 12 lines...
		$content = file_get_contents(__DIR__ . '/Fixtures/ShortCodeExample.txt');

		$textareaHeight = $this->sut->render($content);

		$this->assertEquals('245px', $textareaHeight);
	}

	public function testPassingInLargeContentWillFixHeightToGivenMaxTextareaHeight() {
		// 22 lines...
		$content = file_get_contents(__DIR__ . '/Fixtures/LongCodeExample.txt');

		$textareaHeight = $this->sut->render($content);

		$this->assertEquals('150px', $textareaHeight);
	}

	public function testSupportsInlineNotation() {
		$shortCodeExample = file_get_contents(__DIR__ . '/Fixtures/ShortCodeExample.txt');

		$renderingContextMock = $this
			->getMockBuilder('TYPO3\\CMS\\Fluid\\Core\\Rendering\\RenderingContextInterface')
			->getMock();

		$viewHelperNodeMock = $this
			->getMockBuilder('TYPO3\\CMS\\Fluid\\Core\\Parser\\SyntaxTree\\ViewHelperNode')
			->disableOriginalConstructor()
			->getMock();

		$viewHelperNodeMock
			->expects($this->once())
			->method('evaluateChildNodes')
			->will($this->returnValue($shortCodeExample));

		$this->sut->setRenderingContext($renderingContextMock);
		$this->sut->setViewHelperNode($viewHelperNodeMock);

		$textareaHeight = $this->sut->render();

		$this->assertEquals('245px', $textareaHeight);
	}
}
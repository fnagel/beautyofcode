<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Backend\Hooks;

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
 * PageLayoutViewHooksTestCase
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Backend\Hooks
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class PageLayoutViewHooksTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Backend\Hooks\PageLayoutViewHooks
	 */
	protected $sut;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\CMS\Extbase\Object\ObjectManagerInterface]
	 */
	protected $objectManagerMock;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\CMS\Fluid\View\StandaloneView]
	 */
	protected $viewMock;

	/**
	 *
	 * @var \PHPUnit_Framework_MockObject_MockObject [\TYPO3\CMS\Backend\View\PageLayoutView]
	 */
	protected $pageLayoutViewMock;

	public function setUp() {
		$this->createDatabaseConnectionMock();

		$this->objectManagerMock = $this
			->getMockBuilder('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->disableOriginalConstructor()
			->getMock();

		$this->viewMock = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Mvc\\View\\BackendStandaloneView')
			->disableOriginalConstructor()
			->getMock();

		$this->pageLayoutViewMock = $this
			->getMockBuilder('TYPO3\\CMS\\Backend\\View\\PageLayoutView')
			->getMock();

		$this->sut = new \TYPO3\Beautyofcode\Backend\Hooks\PageLayoutViewHooks();
		$this->sut->injectObjectManager($this->objectManagerMock);
		$this->sut->injectView($this->viewMock);
	}

	/**
	 *
	 * @test
	 */
	public function hookReturnValueIsAnEmptyStringIfListTypeValueDoesNotMatch() {
		$parameters = array(
			'row' => array(
				'list_type' => 'tx_foo_bar',
			),
		);

		$hookReturnValue = $this->sut->getExtensionSummary(
			$parameters,
			$this->pageLayoutViewMock
		);

		$this->assertEmpty($hookReturnValue);
	}

	/**
	 *
	 * @test
	 */
	public function hookReturnValueIsAnEmptyStringIfFlexformDataCannotBeExpandedIntoArray() {
		$parameters = array(
			'row' => array(
				'list_type' => 'beautyofcode_contentrenderer',
				'pi_flexform' => 'Foo?!Bar!',
			),
		);

		$hookReturnValue = $this->sut->getExtensionSummary(
			$parameters,
			$this->pageLayoutViewMock
		);

		$this->assertEmpty($hookReturnValue);
	}

	/**
	 *
	 * @test
	 */
	public function viewExpectsRowAndFlexformDataArrayForProperTemplating() {
		$parameters = array(
			'row' => array(
				'list_type' => 'beautyofcode_contentrenderer',
				'pi_flexform' => file_get_contents(__DIR__ . '/Fixtures/ExampleFlexformShort.xml'),
			),
		);

		$this
			->viewMock
			->expects($this->at(1))
			->method('assign')
			->with('row', $this->equalTo($parameters['row']));

		$this
			->viewMock
			->expects($this->at(2))
			->method('assign');

		$this->sut->getExtensionSummary($parameters, $this->pageLayoutViewMock);
	}
}
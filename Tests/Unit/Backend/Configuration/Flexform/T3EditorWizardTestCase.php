<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Backend\Configuration\Flexform;

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
 * T3EditorWizardTest
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Backend\Configuration\Flexform
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class T3EditorWizardTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\CMS\Backend\Form\FormEngine
	 */
	protected $formEngineMock;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Backend\Configuration\Flexform\T3EditorWizard
	 */
	protected $sut;

	public function setUp() {
		$this->createPackageManagerMock();

		$this->formEngineMock = $this
			->getMockBuilder('TYPO3\\CMS\\Backend\\Form\\FormEngine')
			->disableOriginalConstructor()
			->getMock();

		$GLOBALS['TYPO3_DB'] = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Database\\DatabaseConnection')
			->getMock();
	}

	/**
	 *
	 * @test
	 * @expectedException \TYPO3\Beautyofcode\Backend\Exception\UnableToLoadT3EditorException
	 * @expectedExceptionMessage Cannot instantiate T3editor: ext:t3editor not installed.
	 */
	public function initializeThrowsExceptionIfExtensionIsNotLoaded() {
		$this
			->packageManagerMock
			->expects($this->any())
			->method('isPackageActive')
			->with($this->equalto('t3editor'))
			->will($this->returnValue(FALSE));

		$this->sut = new \TYPO3\Beautyofcode\Backend\Configuration\Flexform\T3EditorWizard();

		$this->sut->initialize();
	}

	/**
	 *
	 * @test
	 * @expectedException \TYPO3\Beautyofcode\Backend\Exception\UnableToLoadT3EditorException
	 * @expectedExceptionMessage Cannot instantiate T3editor: Feature disabled.
	 */
	public function initializeThrowsExceptionIfEditorIsNotEnabledInBeautyofcodeExtensionManagerConfiguration() {
		$this
			->packageManagerMock
			->expects($this->any())
			->method('isPackageActive')
			->with($this->equalTo('t3editor'))
			->will($this->returnValue(TRUE));

		$this->sut = new \TYPO3\Beautyofcode\Backend\Configuration\Flexform\T3EditorWizard();

		$this->sut->initialize();
	}

	/**
	 *
	 * @test
	 */
	public function initializeSuccessIfAllLoadingConditionsMet() {
		$this
			->packageManagerMock
			->expects($this->any())
			->method('isPackageActive')
			->with(
				$this->logicalOr(
					$this->equalTo('t3editor'),
					$this->equalTo('beautyofcode')
				)
			)
			->will($this->returnValue(TRUE));

		$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['beautyofcode'] = serialize(
			array(
				'enable_t3editor' => TRUE,
			)
		);

		$backendDocumentTemplateMock = $this
			->getMockBuilder('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate')
			->disableOriginalConstructor()
			->getMock();

		$pageRendererMock = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Page\\PageRenderer')
			->disableOriginalConstructor()
			->getMock();

		$backendDocumentTemplateMock
			->expects($this->any())
			->method('getPageRenderer')
			->will($this->returnValue($pageRendererMock));

		// necessary data for T3editor constructor
		$GLOBALS['LANG'] = $this
			->getMockBuilder('TYPO3\\CMS\\Lang\\LanguageService')
			->getMock();
		$GLOBALS['BE_USER'] = $this
			->getMockBuilder('TYPO3\\CMS\\Core\\Authentication\\BackendUserAuthentication')
			->getMock();

		$GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config'] = array(
			'type' => 'text',
			'cols' => 40,
			'rows' => 10,
		);

		$parameters = array(
			'row' => array(
				'header' => 'Foo!',
				'bodytext' => 'Bar!',
				'pi_flexform' => file_get_contents(__DIR__ . '/../../Update/Fixtures/FlexformToolsFlexArray2XmlReturnValue.xml')
			),
			'table' => 'tt_content',
			'field' => 'pi_flexform',
			'itemName' => 'tt_content.pi_flexform[data][cCode]',
			'fieldConfig' => array(
				'type' => 'text',
				'size' => '30',
			),
			'fieldChangeFunc' => array(
				'TBE_EDITOR_fieldChanged' => 'javascript:alert(\'Hello World!\');',
			),
		);


		$this->sut = new \TYPO3\Beautyofcode\Backend\Configuration\Flexform\T3EditorWizard(
			$parameters,
			$this->formEngineMock
		);

		$this->sut->injectBackendDocumentTemplate($backendDocumentTemplateMock);

		$this->sut->initialize();

		$this->sut->main($parameters, $this->formEngineMock);

		$this->assertArrayHasKey('item', $parameters);
		$this->assertContains('textarea', $parameters['item']);
	}
}
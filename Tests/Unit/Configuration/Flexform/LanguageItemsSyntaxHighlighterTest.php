<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Configuration\Flexform\LanguageItems;

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
 * Tests for the SyntaxHighlighter brushes
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @runTestsInSeparateProcesses
 */
class LanguageItemsSyntaxHighlighterTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	/**
	 *
	 * @var \TYPO3\CMS\Frontend\Page\PageRepository
	 */
	protected $pageRepositoryMock;

	/**
	 *
	 * @var \TYPO3\CMS\Core\TypoScript\TemplateService
	 */
	protected $templateServiceMock;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	protected $brushDiscoveryMock;

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
		$this->pageRepositoryMock = $this->getMock('TYPO3\\CMS\\Frontend\\Page\\PageRepository');

		$this->templateServiceMock = $this->getMock('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');

		$this->brushDiscoveryMock = $this->getMock(
			'TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService',
			array(),
			array(),
			'BrushDiscoveryService_SyntaxHighlighter'
		);
	}

	public function assertConfiguredSyntaxHighlighter() {
		$this->templateServiceMock->setup = array(
			'plugin.' => array(
				'tx_beautyofcode.' => array(
					'settings.' => array(
						'library' => 'SyntaxHighlighter',
					),
				),
			),
		);

		$this->brushDiscoveryMock
			->expects($this->once())
			->method('discoverBrushes')
			->will($this->returnValue(
				array(
					'SyntaxHighlighter' => array(
						'Bash' => 'Bash/Shell',
						'Php' => 'PHP',
						'Plain' => 'Plain',
						'Python' => 'Python',
						'Sql' => 'SQL / MySQL',
					),
				)
			));
	}

	/**
	 *
	 * @test
	 */
	public function syntaxHighlighterBrushesOverrideTheReturnValue() {
		$this->assertConfiguredSyntaxHighlighter();

		$sut = new \TYPO3\Beautyofcode\Configuration\Flexform\LanguageItems();
		$sut->injectPageRepository($this->pageRepositoryMock);
		$sut->injectTemplateService($this->templateServiceMock);
		$sut->injectBrushDiscoveryService($this->brushDiscoveryMock);
		$sut->initializeObject();

		$newConfig = $sut->getConfiguredLanguages($this->flexformConfigurationFixture);

		$this->assertEquals('Bash', $newConfig['items'][0][1]);
		$this->assertEquals('Php', $newConfig['items'][1][1]);
		$this->assertEquals('Plain', $newConfig['items'][2][1]);
		$this->assertEquals('Python', $newConfig['items'][3][1]);
		$this->assertEquals('Sql', $newConfig['items'][4][1]);
	}
}
?>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TestCase for BrushDiscovery
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushDiscoveryTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Language\LanguageService
	 */
	protected $languageServiceMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\Beautyofcode\Utility\BrushFileFinderUtility
	 */
	protected $fileFinderUtilityMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $configurationMock;

	/**
	 * setUp
	 *
	 * @return void
	 */
	public function setUp() {
		$this->languageServiceMock = $this->getMock('TYPO3\\CMS\\Lang\\LanguageService');

		$this->fileFinderUtilityMock = $this->getMock(
			'TYPO3\\Beautyofcode\\Utility\\BrushFileFinderUtility',
			array('find')
		);

		$this
			->fileFinderUtilityMock
			->expects($this->any())
			->method('in')
			->with($this->equalTo('EXT:beautyofcode/Foo/Bar/'));

		$this
			->fileFinderUtilityMock
			->expects($this->any())
			->method('exclude')
			->with($this->equalTo('.*(-Foo|-FooBar|-Bar)\.js'));

		$this
			->fileFinderUtilityMock
			->expects($this->any())
			->method('stripFromFilename')
			->with(
				$this->logicalOr(
					$this->equalTo('shBrush'),
					$this->equalTo('.js')
				)
			);

		$this
			->fileFinderUtilityMock
			->expects($this->any())
			->method('find')
			->will(
				$this->returnValue(
					array(
						'AppleScript',
						'Bash',
						'Groovy',
						'Perl',
					)
				)
			);

		$this->configurationMock = $this
			->getMockBuilder('TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface')
			->getMock();

		$this
			->configurationMock
			->expects($this->any())
			->method('getLibraryName')
			->will($this->returnValue('HighlighterFoo'));
	}

	/**
	 * assertValidBrushDiscoveryConfiguration
	 *
	 * @return void
	 */
	protected function assertValidBrushDiscoveryConfiguration() {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['BrushDiscovery'] = array(
			'HighlighterFoo' => array(
				'path' => 'EXT:beautyofcode/Foo/Bar/',
				'excludePattern' => '.*(-Foo|-FooBar|-Bar)\.js',
				'prefix' => 'shBrush',
				'suffix' => '.js',
				'dependencies' => array(
					'bash' => 'clike',
					'c' => 'clike',
					'coffeescript' => 'javascript',
					'cpp' => 'c',
					'csharp' => 'clike',
					'go' => 'clike',
					'groovy' => 'clike',
					'java' => 'clike',
					'javascript' => 'clike',
					'php' => 'clike',
					'ruby' => 'clike',
					'scss' => 'css',
					'typoscript' => 'clike',
				),
			),
			'HighlighterBar' => array(
			),
		);
	}

	/**
	 * assertBrushTranslationFixture
	 *
	 * @return void
	 */
	protected function assertBrushTranslationFixture() {
		$this
			->languageServiceMock
			->expects($this->any())
			->method('sL')
			->will($this->returnValue('a-brush-label'));
	}

	public function testDiscoveringBrushesWillFindAllBrushesForASpecificConfiguredLibrary() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes($this->configurationMock);
		$this->assertArrayHasKey('AppleScript', $brushes);
	}

	public function testASpecificLibraryShouldHaveSpecificAmountOfBrushes() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes($this->configurationMock);

		$this->assertGreaterThan(0, count($brushes));
	}

	public function testASpecificLibraryMayHaveDependencies() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$dependencies = $sut->getDependencies($this->configurationMock);

		$this->assertGreaterThan(0, count($dependencies));
	}

	/**
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage No brushes found for the given library HighlighterFoo!
	 */
	public function testInvalidArgumentExceptionIsThrownIfNoBrushesAreSetForGivenLibrary() {
		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$sut->getBrushes($this->configurationMock);
		$sut->getDependencies($this->configurationMock);
	}

	/**
	 *
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage No dependencies found for the given library HighlighterFoo!
	 */
	public function testInvalidArgumentExceptionIsThrownIfNoDependenciesAreSetForGivenLibrary() {
		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$dependencies = $sut->getDependencies($this->configurationMock);
	}

	public function testABrushIsStoredWithItsIdentifierAndALabel() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes($this->configurationMock);

		$this->assertArrayHasKey('AppleScript', $brushes);
		$this->assertEquals('a-brush-label', $brushes['AppleScript']);
	}

	public function testBrushesAreSortedAlphabeticallyByTheirTranslatedLabel() {
		$this->assertValidBrushDiscoveryConfiguration();

		$brushTranslationMap = array(
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:AppleScript', FALSE, 'aaa-First'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Bash', FALSE, 'zzz-Last'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Groovy', FALSE, 'eee-Second'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Perl', FALSE, 'ooo-Third'),
		);

		$this->languageServiceMock->method('sL')->will($this->returnValueMap($brushTranslationMap));

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes($this->configurationMock);

		$this->assertArrayHasKey('AppleScript', $brushes);
		$this->assertEquals('aaa-First', $brushes['AppleScript']);

		$expected = array('AppleScript' => 'aaa-First', 'Groovy' => 'eee-Second', 'Perl' => 'ooo-Third', 'Bash' => 'zzz-Last');

		$this->assertEquals($expected, $brushes);
	}

	public function testBrushLabelsAreSetToTheIncomingIdentifierIfNoEntryInTheLocalizationCatalogueCanBeFound() {
		$this->assertValidBrushDiscoveryConfiguration();

		$brushTranslationMap = array(
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:AppleScript', FALSE, 'aaa-First'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Bash', FALSE, ''),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Groovy', FALSE, 'eee-Second'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Perl', FALSE, 'ooo-Third'),
		);

		$this->languageServiceMock->method('sL')->will($this->returnValueMap($brushTranslationMap));

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes($this->configurationMock);

		$this->assertEquals('Bash', $brushes['Bash']);
	}

	public function testBrushesFilteredByStaticIdentifiers() {
		$this->assertValidBrushDiscoveryConfiguration();

		$this
			->configurationMock
			->expects($this->once())
			->method('hasStaticBrushes')
			->will($this->returnValue(TRUE));

		$this
			->configurationMock
			->expects($this->once())
			->method('getStaticBrushesWithPlainFallback')
			->will(
				$this->returnValue(
					array(
						// these are valid
						'Bash',
						'AppleScript',
						'Plain',
						// nonsense because not returned by fileFinder
						'Bash-Foo',
						'TypoScript',
						'Xml',
					)
				)
			);

		$sut = new \TYPO3\Beautyofcode\Highlighter\BrushDiscovery($this->languageServiceMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes($this->configurationMock);

		$this->assertArrayNotHasKey('Bash-Foo', $brushes);
		$this->assertArrayHasKey('Bash', $brushes);
		$this->assertArrayHasKey('AppleScript', $brushes);
		$this->assertCount(2, $brushes);
	}
}
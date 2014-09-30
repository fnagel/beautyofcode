<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Service;

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
 * TestCase for BrushDiscoveryService
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushDiscoveryServiceTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	protected $languageServiceMock;

	protected $highlighterConfigurationMock;

	protected $fileFinderUtilityMock;

	/**
	 * setUp
	 *
	 * @return void
	 */
	public function setUp() {
		$this->languageServiceMock = $this->getMock('TYPO3\\CMS\\Lang\\LanguageService');

		$this->highlighterConfigurationMock = $this->getMock(
			'TYPO3\\Beautyofcode\\Highlighter\\Configuration\\SyntaxHighlighter'
		);

		$this->fileFinderUtilityMock = $this->getMock(
			'TYPO3\\Beautyofcode\\Utility\\FileFinderUtility',
			array('find')
		);

		$highlighterTestDirectory = $this->createTestDirectory(
			'test', // @note: I've tried with `SyntaxHighlighter` but didn't work. `test` is working because it exists in vfsStream package ?!?!?
			array(
				'shAutoloader.js' => 'autoloader',
				'shBrushAppleScript.js' => 'appleScript-brush',
				'shBrushBash.js' => 'bash-brush',
				'shBrushGroovy.js' => 'groovy-brush',
				'shBrushPerl.js' => 'perl-brush',
				'test.css' => 'apples',
				'.secret.txt' => 'sammon',
			)
		);

		$this
			->fileFinderUtilityMock
			->expects($this->any())
			->method('find')
			->will(
				$this->returnValue(
					GeneralUtility::getFilesInDir(
						$highlighterTestDirectory,
						'js',
						FALSE,
						'',
						'sh(Autoloader|Core|Legacy)\.js'
					)
				)
			);
	}

	/**
	 * assertValidBrushDiscoveryConfiguration
	 *
	 * @return void
	 */
	protected function assertValidBrushDiscoveryConfiguration() {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['beautyofcode']['BrushDiscovery'] = array(
			'HighlighterFoo' => array(
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

	/**
	 *
	 * @test
	 */
	public function discoveringBrushesWillFindAllBrushesForEveryConfiguredLibrary() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes();

		$this->assertArrayHasKey('HighlighterFoo', $brushes);
		$this->assertArrayHasKey('HighlighterBar', $brushes);
	}

	/**
	 *
	 * @test
	 */
	public function aSpecificLibraryShouldHaveSpecificAmountOfBrushes() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes();

		$this->assertGreaterThan(0, count($brushes['HighlighterFoo']));
	}

	/**
	 *
	 * @test
	 */
	public function aSpecificLibraryMayHaveDependencies() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$dependencies = $sut->getDependencies();

		$this->assertGreaterThan(0, count($dependencies['HighlighterFoo']));
	}

	/**
	 *
	 * @test
	 */
	public function noBrushesOrDependenciesIfNoValidDiscoveryConfiguration() {
		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes();
		$dependencies = $sut->getDependencies();

		$this->assertEquals(0, count($brushes));
		$this->assertEquals(0, count($dependencies));
	}

	/**
	 *
	 * @test
	 */
	public function aBrushIsStoredWithItsIdentifierAndALabel() {
		$this->assertValidBrushDiscoveryConfiguration();
		$this->assertBrushTranslationFixture();

		$map = array(
			array('AppleScript', 'applescript'),
			array('Bash', 'bash'),
			array('Groovy', 'groovy'),
			array('Perl', 'perl'),
		);

		$this->highlighterConfigurationMock->method('getBrushAliasByIdentifier')->will($this->returnValueMap($map));

		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes();

		$this->assertArrayHasKey('applescript', $brushes['HighlighterFoo']);
		$this->assertEquals('a-brush-label', $brushes['HighlighterFoo']['applescript']);
	}

	/**
	 *
	 * @test
	 */
	public function brushesAreSortedAlphabeticallyByTheirTranslatedLabel() {
		$this->assertValidBrushDiscoveryConfiguration();

		$brushAliasMap = array(
			array('AppleScript', 'applescript'),
			array('Bash', 'bash'),
			array('Groovy', 'groovy'),
			array('Perl', 'perl'),
		);

		$this->highlighterConfigurationMock->method('getBrushAliasByIdentifier')->will($this->returnValueMap($brushAliasMap));

		$brushTranslationMap = array(
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:AppleScript', FALSE, 'aaa-First'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Bash', FALSE, 'zzz-Last'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Groovy', FALSE, 'eee-Second'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Perl', FALSE, 'ooo-Third'),
		);

		$this->languageServiceMock->method('sL')->will($this->returnValueMap($brushTranslationMap));

		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes();

		$this->assertArrayHasKey('applescript', $brushes['HighlighterFoo']);
		$this->assertEquals('aaa-First', $brushes['HighlighterFoo']['applescript']);

		$expected = array('applescript' => 'aaa-First', 'groovy' => 'eee-Second', 'perl' => 'ooo-Third', 'bash' => 'zzz-Last');

		$this->assertEquals($expected, $brushes['HighlighterFoo']);
	}

	/**
	 *
	 * @test
	 */
	public function brushLabelsAreSetToTheIncomingIdentifierIfNoEntryInTheLocalizationCatalogueCanBeFound() {
		$this->assertValidBrushDiscoveryConfiguration();

		$brushAliasMap = array(
			array('AppleScript', 'applescript'),
			array('Bash', 'bash'),
			array('Groovy', 'groovy'),
			array('Perl', 'perl'),
		);

		$this->highlighterConfigurationMock->method('getBrushAliasByIdentifier')->will($this->returnValueMap($brushAliasMap));

		$brushTranslationMap = array(
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:AppleScript', FALSE, 'aaa-First'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Bash', FALSE, ''),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Groovy', FALSE, 'eee-Second'),
			array('LLL:EXT:beautyofcode/Resources/Private/Language/brush-aliases.xlf:Perl', FALSE, 'ooo-Third'),
		);

		$this->languageServiceMock->method('sL')->will($this->returnValueMap($brushTranslationMap));

		$sut = new \TYPO3\Beautyofcode\Service\BrushDiscoveryService($this->languageServiceMock);
		$sut->injectHighlighterConfiguration($this->highlighterConfigurationMock);
		$sut->injectFileFinderUtility($this->fileFinderUtilityMock);
		$sut->initializeObject();

		$brushes = $sut->getBrushes();

		$this->assertEquals('Bash', $brushes['HighlighterFoo']['bash']);
	}
}
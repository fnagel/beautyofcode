<?php
namespace TYPO3\Beautyofcode\Tests\Unit\Utility;

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
 * TestCase for the BrushFileFinderUtility.
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Utility
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushFileFinderUtilityTestCase extends \TYPO3\Beautyofcode\Tests\UnitTestCase {

	/**
	 * @var string
	 */
	protected $directoryFixture = '';

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\Beautyofcode\Utility\BrushFileFinderUtility
	 */
	protected $sut;

	/**
	 * setUp
	 *
	 * @return void
	 */
	public function setUp() {
		$this->directoryFixture = $this->createTestDirectory(
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

		$this->sut = new \TYPO3\Beautyofcode\Utility\BrushFileFinderUtility();
	}

	public function testReturnedFileNamesDoNotContainFilesWhichMatchesExcludePattern() {
		$files = $this->sut
			->in($this->directoryFixture)
			->exclude('sh(Autoloader|Core|Legacy)\.js')
			->find('js');

		$expectedFilenames = array(
			'shBrushAppleScript.js',
			'shBrushBash.js',
			'shBrushGroovy.js',
			'shBrushPerl.js',
		);
		$actualFilenames = array_values($files);

		$this->assertEquals($expectedFilenames, $actualFilenames);
	}

	public function testReturnedFileNamesAreStrippedOffByThePassedRemovalStrings() {
		$files = $this->sut
			->in($this->directoryFixture)
			->exclude('sh(Autoloader|Core|Legacy)\.js')
			->stripFromFilename('shBrush')
			->stripFromFilename('.js')
			->find('js');

		$expectedFilenames = array(
			'AppleScript',
			'Bash',
			'Groovy',
			'Perl',
		);
		$actualFilenames = array_values($files);

		$this->assertEquals($expectedFilenames, $actualFilenames);
	}


}
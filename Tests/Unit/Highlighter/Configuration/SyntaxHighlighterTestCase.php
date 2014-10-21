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
 * SyntaxHighlighterTestCase
 *
 * @package \TYPO3\Beautyofcode\Tests\Unit\Highlighter\Configuration
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class SyntaxHighlighterTestCase extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\Configuration\SyntaxHighlighter
	 */
	protected $sut;

	public function setUp() {
		$this->sut = new \TYPO3\Beautyofcode\Highlighter\Configuration\SyntaxHighlighter(
			array(
				'ColdFusion' => 'coldfusion',
				'TypoScript' => 'typoscript',
			),
			array(
				'Prism' => array(
					'markup' => 'xml',
				),
			)
		);
	}

	public function testFailSafeBrushAliasRetrievalWorksForAllOtherKnownHighlighterLibraries() {
		$brushAliasAfterLibrarySwitching = 'markup';

		$failSafeBrushAlias = $this->sut->getFailSafeBrushAlias($brushAliasAfterLibrarySwitching);

		$this->assertEquals('xml', $failSafeBrushAlias);
	}

	public function testFailSafeBrushAliasReturnsIncomingValueIfHighlighterHasBrushAlias() {
		$brushAlias = 'typoscript';

		$failSafeBrushAlias = $this->sut->getFailSafeBrushAlias($brushAlias);

		$this->assertEquals($brushAlias, $failSafeBrushAlias);
	}

	public function testBrushAliasIsReturnedIfIdentifierIsFoundInMap() {
		$brushIdentifier = 'ColdFusion';

		$brushAlias = $this->sut->getBrushAliasByIdentifier($brushIdentifier);

		$this->assertEquals('coldfusion', $brushAlias);
	}

	public function testBrushAliasEqualsBrushIdentifierIfNotFoundInMap() {
		$brushIdentifier = 'WizardOfOz';

		$brushAlias = $this->sut->getBrushAliasByIdentifier($brushIdentifier);

		$this->assertEquals($brushIdentifier, $brushAlias);
	}

	private function getFlexformFixture() {
		$flexform = new \TYPO3\Beautyofcode\Domain\Model\Flexform();
		$flexform->setCLabel('The label');
		$flexform->setCLang('typoscript');
		$flexform->setCCode('page = PAGE\npage.10 = TEXT\npage.10.value = Hello World!');
		$flexform->setCHighlight('1,2-3,8');
		$flexform->setCCollapse('1');
		$flexform->setCGutter('1');

		return $flexform;
	}

	public function testSettingAnEmptyValueForSyntaxHighlighterWillSkipTheOutputForTheSetting() {
		$flexform = $this->getFlexformFixture();
		$flexform->setCCollapse('');

		$this->assertNotContains('collapse', $this->sut->getClassAttributeString($flexform));
	}

	public function testSettingAutoValueForSyntaxHighlighterWillSkipTheOutputForTheSetting() {
		$flexform = $this->getFlexformFixture();
		$flexform->setCGutter('auto');

		$this->assertNotContains('gutter', $this->sut->getClassAttributeString($flexform));
	}

	public function testHighlightSettingHasSpecialFormattingForSyntaxHighlighter() {
		$flexform = $this->getFlexformFixture();
		$this->assertContains('highlight: [', $this->sut->getClassAttributeString($flexform));
	}

	public function testHighlightSettingWilllBeExpandedForSyntaxHighlighter() {
		$flexform = $this->getFlexformFixture();
		$this->assertContains('highlight: [1,2,3,8]', $this->sut->getClassAttributeString($flexform));
	}
}
<?php
namespace TYPO3\Beautyofcode\Highlighter\Configuration;

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
 * SyntaxHighlighter
 *
 * @package \TYPO3\Beautyofcode\Highlighter\Configuration
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class SyntaxHighlighter
	extends AbstractConfiguration {

	/**
	 *
	 * @var array
	 */
	protected $failSafeBrushAliasMap = array(
		'Prism' => array(
			'c' => 'cpp',
			'coffeescript' => 'javascript',
			'gherkin' => 'plain',
			'go' => 'plain',
			'http' => 'plain',
			'markup' => 'xml',
			'scss' => 'sass',
		),
	);

	/**
	 * A Brush identifier / alias map
	 *
	 * With SyntaxHighlighter, a Brush identifier may have a Brush alias which
	 * is used in the class attribute of the HTML element and defined within
	 * the JavaScript brush resource.
	 *
	 * @var array
	 */
	protected $brushIdentifierAliasMap = array(
		'AppleScript' => 'applescript',
		'AS3' => 'actionscript3',
		'Bash' => 'bash',
		'ColdFusion' => 'coldfusion',
		'Cpp' => 'cpp',
		'CSharp' => 'csharp',
		'Css' => 'css',
		'Delphi' => 'delphi',
		'Diff' => 'diff',
		'Erlang' => 'erlang',
		'Groovy' => 'groovy',
		'Java' => 'java',
		'JavaFX' => 'javafx',
		'JScript' => 'javascript',
		'Perl' => 'perl',
		'Php' => 'php',
		'PowerShell' => 'powershell',
		'Python' => 'python',
		'Ruby' => 'ruby',
		'Scala' => 'scala',
		'Sql' => 'sql',
		'Typoscript' => 'typoscript',
		'Vb' => 'vbnet',
		'Xml' => 'xml',
	);

	/**
	 * prepareRegisteredBrushes
	 *
	 * @param array $brushStack
	 * @return void
	 */
	public function prepareRegisteredBrushes(array $brushStack = array()) {
		$brushes = array();

		$brushAliasIdentifierMap = array_flip($this->brushIdentifierAliasMap);

		foreach ($brushStack as $brushAlias) {
			$brushIdentifier = $brushAliasIdentifierMap[$brushAlias];

			$brushes[$brushAlias] = $brushIdentifier;
		}

		return parent::prepareRegisteredBrushes($brushes);
	}

	/**
	 * getClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform
	 * @return string
	 */
	public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform) {
		$configurationItems = array();

		$classAttributeConfigurationStack = array(
			'highlight' => GeneralUtility::expandList($flexform->getCHighlight()),
			'gutter' => $flexform->getCGutter(),
			'collapse' => $flexform->getCCollapse(),
		);

		foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if (TRUE === in_array($configurationValue, array('', 'auto'))) {
				continue;
			}

			if ($configurationKey === 'highlight') {
				$key = $configurationKey;
				$value = sprintf('[%s]', $configurationValue);
			} else {
				$key = $configurationKey;
				$value = var_export((boolean) $configurationValue, TRUE);
			}

			$configurationItems[] = sprintf('%s: %s', $key, $value);
		}

		return '; ' . implode('; ', $configurationItems);
	}
}
?>
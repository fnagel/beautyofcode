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
	 * A CSS class/label map for the select box
	 *
	 * Key is the brush string from TS Setup; Value is an array with the CSS
	 * class in key 0 and the label for the select box in key 1
	 *
	 * @var array
	 */
	protected $brushIdentifierAliasLabelMap = array(
		'AppleScript' => array('applescript', 'AppleScript'),
		'AS3' => array('actionscript3', 'Actionscript 3'),
		'Bash' => array('bash', 'Bash / Shell'),
		'ColdFusion' => array('coldfusion', 'ColdFusion'),
		'Cpp' => array('cpp', 'C / C++'),
		'CSharp' => array('csharp', 'C#'),
		'Css' => array('css', 'CSS'),
		'Delphi' => array('delphi', 'Delphi / Pas / Pascal'),
		'Diff' => array('diff', 'Diff / Patch'),
		'Erlang' => array('erlang', 'Erlang'),
		'Groovy' => array('groovy', 'Groovy'),
		'Java' => array('java', 'Java'),
		'JavaFX' => array('javafx', 'Java FX'),
		'JScript' => array('javascript', 'Java-Script'),
		'Perl' => array('perl', 'Perl'),
		'Php' => array('php', 'PHP'),
		'PowerShell' => array('powershell', 'Power-Shell'),
		'Python' => array('python', 'Python'),
		'Ruby' => array('ruby', 'Ruby on Rails'),
		'Scala' => array('scala', 'Scala'),
		'Sql' => array('sql', 'SQL / MySQL'),
		'Typoscript' => array('typoscript', 'Typoscript'),
		'Vb' => array('vbnet', 'Virtual Basic / .Net'),
		'Xml' => array('xml', 'XML / XSLT / XHTML / HTML'),
	);

	/**
	 * addRegisteredBrushes
	 *
	 * @param array $brushStack
	 * @return void
	 */
	public function addRegisteredBrushes(array $brushStack = array()) {
		$brushes = array(
			'plain' => 'shBrushPlain.js',
		);

		foreach ($brushStack as $brush) {
			list($cssTag, ) = $this->brushIdentifierAliasLabelMap[$brush];
			$brushPath = 'shBrush' . $brush .'.js';

			$brushes[$cssTag] = $brushPath;
		}

		$this->brushLoaderView->assign('brushes', $brushes);
		$this->brushLoaderView->render();
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
			'highlight' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($flexform->getCHighlight()),
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
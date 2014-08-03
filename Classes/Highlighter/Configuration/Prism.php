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
 * Prism
 *
 * @package \TYPO3\Beautyofcode\Highlighter\Configuration
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class Prism
	extends \TYPO3\Beautyofcode\Highlighter\AbstractConfiguration {

	protected $failSafeBrushIdentifierMap = array(
		'SyntaxHighlighter' => array(
			'applescript' => 'javascript',
			'actionscript3' => 'javascript',
			'coldfusion' => 'markup',
			'delphi' => 'plain',
			'diff' => 'plain',
			'erlang' => 'plain',
			'javafx' => 'java',
			'perl' => 'c',
			'powershell' => 'bash',
			'sass' => 'scss',
			'scala' => 'java',
			'vb' => 'plain',
			'xml' => 'markup',
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
		'bash' => array('bash', 'Bash / Shell'),
		'c' => array('c', 'C / C++'),
		'clike' => array('clike', 'C-Like'),
		'coffeescript' => array('coffeescript', 'Coffeescript'),
		'cpp' => array('cpp', 'C / C++'),
		'csharp' => array('csharp', 'C#'),
		'css' => array('css', 'CSS'),
		'gherkin' => array('gherkin', 'Gherkin'),
		'go' => array('go', 'Go'),
		'groovy' => array('groovy', 'Groovy'),
		'http' => array('http', 'HTTP'),
		'java' => array('java', 'Java'),
		'javascript' => array('javascript', 'JavaScript'),
		'markup' => array('markup', 'XML / XSLT / XHTML / HTML'),
		'php' => array('php', 'PHP'),
		'python' => array('python', 'Python'),
		'ruby' => array('ruby', 'Ruby'),
		'scss' => array('scss', 'SCSS'),
		'sql' => array('sql', 'SQL'),
		'typoscript' => array('typoscript', 'TypoScript'),
	);

	/**
	 * getAutoloaderBrushMap
	 *
	 * The Prism highlighter doesn't have any autoloader, but as this method
	 * needs to be implemented, it returns an empty array.
	 *
	 * @return array
	 */
	public function getAutoloaderBrushMap() {
		return array();
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
			'data-line' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($flexform->getCHighlight()),
		);

		foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if (TRUE === in_array($configurationValue, array('', 'auto'))) {
				continue;
			}

			$configurationItems[] = sprintf('%s="%s"', $configurationKey, $configurationValue);
		}

		return ' ' . implode(' ', $configurationItems);
	}
}
?>
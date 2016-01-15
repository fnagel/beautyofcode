<?php
namespace TYPO3\Beautyofcode\Highlighter\Configuration;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * SyntaxHighlighter
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\Highlighter\Configuration
 */
class SyntaxHighlighter extends AbstractConfiguration {

	/**
	 * Failsafe brush alias map
	 *
	 * Fallback from one highlighter engine to another.
	 * Key Prism SH CSS class and value is SH CSS class.
	 *
	 * @var array
	 */
	protected $failSafeBrushAliasMap = array(
		'Prism' => array(
			'actionscript' => 'actionscript3',
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
		'Sass' => array('sass', 'Sass'),
		'Scala' => array('scala', 'Scala'),
		'Sql' => array('sql', 'SQL / MySQL'),
		'Typoscript' => array('typoscript', 'Typoscript'),
		'Vb' => array('vbnet', 'Virtual Basic / .Net'),
		'Xml' => array('xml', 'XML / XSLT / XHTML / HTML'),
	);

	/**
	 * GetAutoloaderBrushMap
	 *
	 * @return array
	 */
	public function getAutoloaderBrushMap() {
		$brushes = array();

		$configuredBrushes = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(
			',',
			$this->settings['brushes']
		);

		$brushes['plain'] = 'shBrushPlain.js';

		foreach ($configuredBrushes as $brush) {
			list($cssTag, ) = $this->brushIdentifierAliasLabelMap[$brush];
			$brushPath = 'shBrush' . $brush . '.js';

			$brushes[$cssTag] = $brushPath;
		}

		return $brushes;
	}

	/**
	 * GetClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform Flexform
	 *
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

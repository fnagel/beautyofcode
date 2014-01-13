<?php
namespace TYPO3\Beautyofcode\Domain\Model;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 * Domain model object for the flexform configuration of a plugin instance
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class Flexform extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {

	/**
	 *
	 * @var string
	 */
	protected $cLabel;

	/**
	 *
	 * @var string
	 */
	protected $cLang;

	/**
	 *
	 * @var string
	 */
	protected $cCode;

	/**
	 *
	 * @var string
	 */
	protected $cHighlight;

	/**
	 *
	 * @var string
	 */
	protected $cCollapse;

	/**
	 *
	 * @var string
	 */
	protected $cGutter;

	/**
	 *
	 * @var string
	 */
	protected $cToolbar;

	/**
	 *
	 * @var array
	 */
	protected $brushes = array();

	/**
	 *
	 * @var string
	 */
	protected $languageFallback = 'plain';

	/**
	 * A map of $brush => $cssTag for the lazy loader of v3
	 *
	 * @var array
	 */
	protected $standaloneBrushCssClassMap = array(
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

	public function setCLabel($cLabel) {
		$this->cLabel = $cLabel;
	}

	public function getCLabel() {
		return $this->cLabel;
	}

	public function setCLang($cLang) {
		$this->cLang = $cLang;
	}

	public function getCLang() {
		return $this->cLang;
	}

	public function setCCode($cCode) {
		$this->cCode = $cCode;
	}

	public function getCCode() {
		return $this->cCode;
	}

	public function setCHighlight($cHighlight) {
		$this->cHighlight = $cHighlight;
	}

	public function getCHighlight() {
		return $this->cHighlight;
	}

	public function setCCollapse($cCollapse) {
		$this->cCollapse = $cCollapse;
	}

	public function getCCollapse() {
		return $this->cCollapse;
	}

	public function setCGutter($cGutter) {
		$this->cGutter = $cGutter;
	}

	public function getCGutter() {
		return $this->cGutter;
	}

	public function setCToolbar($cToolbar) {
		$this->cToolbar = $cToolbar;
	}

	public function getCToolbar() {
		return $this->cToolbar;
	}

	public function setBrushes($brushes = array()) {
		$this->brushes = $brushes;
	}

	public function getLanguage() {
		return $this->cLang ? $this->cLang : $this->languageFallback;
	}

	/**
	 * Returns the class attribute configuration for the jquery (v2) library
	 *
	 * @return string
	 */
	public function getJqueryClassAttributeConfiguration() {
		$configurationItems = array();

		$classAttributeConfigurationStack = array(
			'highlight' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($this->cHighlight),
			'gutter' => $this->cGutter,
			'toolbar' => $this->cToolbar,
			'collapse' => $this->cCollapse,
		);

		foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if (TRUE === in_array($configurationValue, array('', 'auto'))) {
				continue;
			}

			if ($configurationKey === 'highlight') {
				$key = $configurationKey;
				$value = sprintf('[%s]', $configurationValue);
			} else {
				$key = $configurationValue ? '' : 'no-';
				$value = $configurationKey;
			}

			$configurationItems[] = sprintf('boc-%s%s', $key, $value);
		}

		return ' ' . implode(' ', $configurationItems);
	}

	/**
	 * Returns the class attribute configuration for the standalone (v2) library
	 *
	 * @return string
	 */
	public function getStandaloneClassAttributeConfiguration() {
		$configurationItems = array();

		$classAttributeConfigurationStack = array(
			'highlight' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($this->cHighlight),
			'gutter' => $this->cGutter,
			// no toolbar
			'collapse' => $this->cCollapse,
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

	/**
	 * Returns the class attribute configuration string suitable for prism
	 *
	 * @return string
	 */
	public function getPrismClassAttributeConfiguration() {
		$configurationItems = array();
		$classAttributeConfigurationStack = array(
			'data-line' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($this->cHighlight),
		);

		foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
			if (TRUE === in_array($configurationValue, array('', 'auto'))) {
				continue;
			}

			$configurationItems[] = sprintf('%s="%s"', $configurationKey, $configurationValue);
		}

		return ' ' . implode(' ', $configurationItems);
	}

	/**
	 * Returns an array of brush CSS name + ressource file name
	 *
	 * @return array
	 */
	public function getStandaloneBrushesForAutoloader() {
		$brushes = array();

		$configuredBrushes = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(
			',',
			$this->brushes
		);

		$brushes['plain'] = 'shBrushPlain.js';

		foreach ($configuredBrushes as $brush) {
			$cssTag = $this->standaloneBrushCssClassMap[$brush];
			$brushPath = 'shBrush' . $brush . '.js';

			$brushes[$cssTag] = $brushPath;
		}

		return $brushes;
	}
}
?>
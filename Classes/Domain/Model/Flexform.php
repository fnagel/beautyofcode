<?php
namespace TYPO3\Beautyofcode\Domain\Model;

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

use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;

/**
 * Domain model object for the flexform configuration of a plugin instance
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class Flexform extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $highlighterConfiguration;

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
	 * @var array
	 */
	protected $brushes = array();

	/**
	 * Default settings from settings.defaults
	 *
	 * @var array
	 */
	protected $typoscriptDefaults = array();

	/**
	 *
	 * @var string
	 */
	protected $languageFallback = 'plain';

	/**
	 * injectHighlighterConfiguration
	 *
	 * @param ConfigurationInterface $highlighterConfiguration
	 * @return void
	 */
	public function injectHighlighterConfiguration(ConfigurationInterface $highlighterConfiguration) {
		$this->highlighterConfiguration = $highlighterConfiguration;
	}

	/**
	 * setCLabel
	 *
	 * @param string $cLabel
	 * @return void
	 */
	public function setCLabel($cLabel) {
		$this->cLabel = $cLabel;
	}

	/**
	 * getCLabel
	 *
	 * @return string
	 */
	public function getCLabel() {
		return $this->cLabel;
	}

	/**
	 * setCLang
	 *
	 * @param string $cLang
	 * @return void
	 */
	public function setCLang($cLang) {
		$this->cLang = $cLang;
	}

	/**
	 * getCLang
	 *
	 * @return string
	 */
	public function getCLang() {
		return $this->cLang;
	}

	/**
	 * setCCode
	 *
	 * @param string $cCode
	 * @return void
	 */
	public function setCCode($cCode) {
		$this->cCode = $cCode;
	}

	/**
	 * getCCode
	 *
	 * @return string
	 */
	public function getCCode() {
		return $this->cCode;
	}

	/**
	 * setCHihglight
	 *
	 * @param string $cHighlight
	 * @return void
	 */
	public function setCHighlight($cHighlight) {
		$this->cHighlight = $cHighlight;
	}

	/**
	 * getCHighlight
	 *
	 * @return string
	 */
	public function getCHighlight() {
		return $this->cHighlight;
	}

	/**
	 * setCCollapse
	 *
	 * @param string $cCollapse
	 * @return void
	 */
	public function setCCollapse($cCollapse) {
		$this->cCollapse = $cCollapse;
	}

	/**
	 * getCCollapse
	 *
	 * @return string
	 */
	public function getCCollapse() {
		return $this->cCollapse;
	}

	/**
	 * setCGutter
	 *
	 * @param string $cGutter
	 * @return void
	 */
	public function setCGutter($cGutter) {
		$this->cGutter = $cGutter;
	}

	/**
	 * getCGutter
	 *
	 * @return string
	 */
	public function getCGutter() {
		return $this->cGutter;
	}

	/**
	 * getIsGutterActive
	 *
	 * @return boolean
	 */
	public function getIsGutterActive() {
		$isOffForInstance = '0' === $this->cGutter;
		$isOnForInstance = '1' === $this->cGutter;
		$useDefault = 'auto' === $this->cGutter;
		$isDefaultSet = isset($this->typoscriptDefaults['gutter']);

		if ($isOffForInstance) {
			return FALSE;
		} else if ($isOnForInstance) {
			return TRUE;
		} else if ($useDefault && $isDefaultSet) {
			return (bool) $this->typoscriptDefaults['gutter'];
		} else {
			return FALSE;
		}
	}

	/**
	 * setTyposcriptDefaults
	 *
	 * @param array $typoscriptDefaults
	 * @return void
	 */
	public function setTyposcriptDefaults($typoscriptDefaults = array()) {
		$this->typoscriptDefaults = $typoscriptDefaults;
	}

	/**
	 * getLanguage
	 *
	 * @return string
	 */
	public function getLanguage() {
		$language = $this->cLang ? $this->cLang : $this->languageFallback;

		return $this->highlighterConfiguration->getFailSafeBrushIdentifier($language);
	}

	/**
	 * getClassAttributeString
	 *
	 * @return string
	 */
	public function getClassAttributeString() {
		return $this->highlighterConfiguration->getClassAttributeString($this);
	}

	/**
	 * Returns an array of brush CSS name + ressource file name
	 *
	 * @return array
	 */
	public function getAutoloaderBrushMap() {
		return $this->highlighterConfiguration->getAutoloaderBrushMap();
	}
}
?>
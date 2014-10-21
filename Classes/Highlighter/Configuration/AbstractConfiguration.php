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
 * AbstractConfiguration
 *
 * @package \TYPO3\Beautyofcode\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
abstract class AbstractConfiguration implements \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface {

	/**
	 * @var array
	 */
	protected $identifierAliases = array();

	/**
	 * @var array
	 */
	protected $failsafeAliases = array();

	/**
	 * Constructor
	 *
	 * Receives the identifier-to-alias and failsafe alias maps for the
	 * concrete configuration.
	 *
	 * @param array $identifierAliases
	 * @param array $failsafeAliases
	 * @return \TYPO3\Beautyofcode\Highlighter\Configuration\AbstractConfiguration
	 */
	public function __construct(array $identifierAliases, array $failsafeAliases) {
		$this->identifierAliases = $identifierAliases;
		$this->failsafeAliases = $failsafeAliases;
	}

	/**
	 * getFailSafeBrushAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getFailSafeBrushAlias($brushAlias) {
		if ($this->hasBrushAlias($brushAlias)) {
			return $brushAlias;
		}

		foreach ($this->failsafeAliases as $foreignLibraryMap) {
			if (isset($foreignLibraryMap[$brushAlias])) {
				$failSafeBrushAlias = $foreignLibraryMap[$brushAlias];
				break;
			}
		}

		return $failSafeBrushAlias;
	}

	/**
	 * hasBrushAlias
	 *
	 * @param string $brushAlias
	 * @return boolean
	 */
	protected function hasBrushAlias($brushAlias) {
		foreach ($this->identifierAliases as $alias) {
			if ($alias === $brushAlias) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * getBrushAliasByIdentifier
	 *
	 * @param string $brushIdentifier
	 * @return string
	 */
	public function getBrushAliasByIdentifier($brushIdentifier) {
		if (isset($this->identifierAliases[$brushIdentifier])) {
			return $this->identifierAliases[$brushIdentifier];
		}

		return $brushIdentifier;
	}

	/**
	 * getBrushIdentifierByAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getBrushIdentifierByAlias($brushAlias) {
		$flippedMap = array_flip($this->identifierAliases);

		return isset($flippedMap[$brushAlias]) ? $flippedMap[$brushAlias] : $brushAlias;
	}

	/**
	 * Flags if the active highlighter configuration has static brushes configured.
	 *
	 * @param array $settings
	 * @return bool
	 */
	public function hasStaticBrushes(array $settings = array()) {
		return isset($settings['brushes']) && '' !== trim($settings['brushes']);
	}

	/**
	 * Returns the static brushes array, with added `plain` brush if not configured
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getStaticBrushesWithPlainFallback(array $settings = array()) {
		$staticIdentifiers = GeneralUtility::trimExplode(',', $settings['brushes']);
		$staticIdentifierKeys = array_flip($staticIdentifiers);

		$plainIdentifier = $this->getPlainBrushIdentifier();

		if (!isset($staticIdentifierKeys[$plainIdentifier])) {
			$staticIdentifiers[] = $plainIdentifier;
		}

		return $staticIdentifiers;
	}

	/**
	 * getLibraryNam
	 *
	 * @return string
	 */
	public function getLibraryName() {
		$reflectionClass = new \ReflectionClass($this);

		return $reflectionClass->getShortName();
	}

	/**
	 * getPlainBrushIdentifier
	 *
	 * @return string
	 */
	abstract protected function getPlainBrushIdentifier();
}
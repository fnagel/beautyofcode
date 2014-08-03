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
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * __construct
	 *
	 * @param array $settings
	 * @return void
	 */
	public function __construct(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * getFailSafeBrushIdentifier
	 *
	 * @param string $brushIdentifier
	 * @return string
	 */
	public function getFailSafeBrushIdentifier($brushIdentifier) {
		$failSafeBrushIdentifier = $brushIdentifier;

		foreach ($this->failSafeBrushIdentifierMap as $foreignLibrary => $foreignLibraryMap) {
			if (isset($foreignLibraryMap[$brushIdentifier])) {
				$failSafeBrushIdentifier = $foreignLibraryMap[$brushIdentifier];
				break;
			}
		}

		return $failSafeBrushIdentifier;
	}

	/**
	 * hasBrushIdentifier
	 *
	 * @param string $brushIdentifier
	 * @return boolean
	 */
	public function hasBrushIdentifier($brushIdentifier) {
		return isset($this->brushIdentifierAliasLabelMap[$brushIdentifier]);
	}


	/**
	 * getBrushIdentifierAliasAndLabel
	 *
	 * @param string $brushIdentifier
	 * @return array
	 */
	public function getBrushIdentifierAliasAndLabel($brushIdentifier) {
		return $this->brushIdentifierAliasLabelMap[$brushIdentifier];
	}
}
?>
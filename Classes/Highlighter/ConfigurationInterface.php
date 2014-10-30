<?php
namespace TYPO3\Beautyofcode\Highlighter;

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
 * ConfigurationInterface
 *
 * @package \TYPO3\Beautyofcode\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
interface ConfigurationInterface {

	/**
	 * getFailSafeBrushAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getFailSafeBrushAlias($brushAlias);

	/**
	 * getClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform
	 * @return string
	 */
	public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform);

	/**
	 * getBrushAliasByIdentifier
	 *
	 * @param string $brushIdentifier
	 * @return string
	 */
	public function getBrushAliasByIdentifier($brushIdentifier);

	/**
	 * getBrushIdentifierByAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getBrushIdentifierByAlias($brushAlias);

	/**
	 * Flags if the active highlighter configuration has static brushes configured.
	 *
	 * @param array $settings
	 * @return bool
	 */
	public function hasStaticBrushes(array $settings = array());

	/**
	 * Returns the static brushes array, with added `plain` brush if not configured
	 *
	 * @param array $settings Is set by the Configuration implementation
	 * @return array
	 */
	public function getStaticBrushesWithPlainFallback(array $settings = array());

	/**
	 * getLibraryName
	 *
	 * @return string
	 */
	public function getLibraryName();
}
<?php
namespace TYPO3\Beautyofcode\Highlighter;

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
 * ConfigurationInterface
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\Highlighter
 */
interface ConfigurationInterface {

	/**
	 * GetFailSafeBrushAlias
	 *
	 * @param string $brushAlias Brush alias
	 *
	 * @return string
	 */
	public function getFailSafeBrushAlias($brushAlias);

	/**
	 * HasBrushIdentifier
	 *
	 * @param string $brushIdentifier Brush identifier
	 *
	 * @return bool
	 */
	public function hasBrushIdentifier($brushIdentifier);

	/**
	 * HasBrushAlias
	 *
	 * @param string $brushAlias Brush alias
	 *
	 * @return bool
	 */
	public function hasBrushAlias($brushAlias);

	/**
	 * GetBrushIdentifierAliasAndLabel
	 *
	 * @param string $brushIdentifier Brush identifier
	 *
	 * @return array
	 */
	public function getBrushIdentifierAliasAndLabel($brushIdentifier);

	/**
	 * GetAutoloaderBrushMap
	 *
	 * @return array
	 */
	public function getAutoloaderBrushMap();

	/**
	 * GetClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform Flexform
	 *
	 * @return string
	 */
	public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform);
}

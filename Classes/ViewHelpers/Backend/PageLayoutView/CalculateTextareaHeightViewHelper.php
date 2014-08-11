<?php
namespace TYPO3\Beautyofcode\ViewHelpers\Backend\PageLayoutView;

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
 * CalculateTextareaHeightViewHelper
 *
 * @package \TYPO3\Beautyofcode\ViewHelpers\Backend\PageLayoutView
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class CalculateTextareaHeightViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 *
	 * @var integer
	 */
	const MAX_TEXTAREA_LINES = 15;

	/**
	 *
	 * @var integer
	 */
	const MAX_TEXTAREA_HEIGHT = 150;

	/**
	 *
	 * @var integer
	 */
	const SMALL_TEXTAREA_FACTOR = 20;

	/**
	 *
	 * @var integer
	 */
	const SMALL_TEXTAREA_ADDITION = 5;

	/**
	 * render
	 *
	 * Calculates and returns a textarea height attribute value string depending
	 * of the incoming $content amount. You can finetune the height dimension by
	 * modifying the $maxTextarea* and $smallTextarea* viewhelper arguments.
	 *
	 * @param string $content
	 * @param string $unit
	 * @param integer $maxTextareaLines
	 * @param integer $maxTextareaHeight
	 * @param integer $smallTextareaFactor
	 * @param integer $smallTextareaAddition
	 * @return string
	 */
	public function render(
		$content = NULL,
		$unit = 'px',
		$maxTextareaLines = 15,
		$maxTextareaHeight = 150,
		$smallTextareaFactor = 20,
		$smallTextareaAddition = 5
	) {
		if (is_null($content)) {
			$content = $this->renderChildren();
		}

		$lines = preg_split("/(\n)/", $content);
		$proxyLines = sizeof($lines);

		if ($proxyLines > $maxTextareaLines) {
			$textareaHeight = $maxTextareaHeight;
		} else {
			$textareaHeight = $proxyLines * $smallTextareaFactor;
			$textareaHeight += $smallTextareaAddition;
		}

		return sprintf('%s%s', $textareaHeight, $unit);
	}
}
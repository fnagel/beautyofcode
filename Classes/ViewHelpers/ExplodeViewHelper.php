<?php
namespace TYPO3\Beautyofcode\ViewHelpers;

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
 * Fluid view helper around GeneralUtility::trimExplode()
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\ViewHelpers
 */
class ExplodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Renders the view helper
	 *
	 * @param string $value CSV list string
	 * @param string $delimiter Delimiter, defaults to ,
	 * @param bool $removeEmptyValues Flags if empty values should be removed
	 *
	 * @return array
	 */
	protected function render($value = NULL, $delimiter = ',', $removeEmptyValues = FALSE) {
		if (TRUE === is_null($value)) {
			$value = $this->renderChildren();
		}

		return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($delimiter, $value, $removeEmptyValues);
	}

}

<?php
namespace TYPO3\Beautyofcode\ViewHelpers;

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
 * Fluid view helper around GeneralUtility::trimExplode()
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ExplodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 *
	 * @param string $value
	 * @param string $delimiter
	 * @param boolean $removeEmptyValues
	 * @return array
	 */
	protected function render($value = NULL, $delimiter = ',', $removeEmptyValues = FALSE) {
		if (TRUE === is_null($value)) {
			$value = $this->renderChildren();
		}

		return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($delimiter, $value, $removeEmptyValues);
	}

}
?>
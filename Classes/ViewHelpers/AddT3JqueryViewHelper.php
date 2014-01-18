<?php
namespace TYPO3\Beautyofcode\ViewHelpers;

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

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3jquery')) {
	require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3jquery') . 'class.tx_t3jquery.php');
}

/**
 * This view helper calls the necessary methods for injecting t3jquery to the page
 *
 * @package TYPO3\Beautyofcode\ViewHelpers
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AddT3JqueryViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Calls \tx_t3jquery:.addJqJS if t3jquery library was loaded
	 *
	 * You can use the return value to make further decisions within your template,
	 * e.g. if to load a custom jQuery library
	 *
	 * @return boolean TRUE if t3jquery is loaded
	 */
	public function render() {
		$isT3JqueryLoaded = T3JQUERY === TRUE;

		if ($isT3JqueryLoaded) {
			\tx_t3jquery::addJqJS();
		}

		return $isT3JqueryLoaded;
	}
}
?>
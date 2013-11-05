<?php
namespace FNagel\Beautyofcode\Utility;

/***************************************************************
 * Copyright notice
 *
 * (c) 2010-2013 Felix Nagel (info@felixnagel.com)
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
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Utility class for beautyofcode
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */
class GeneralUtility {

	/**
	 * Function to solve path with FILE: and EXT:
	 *
	 * @param	string	path to directory
	 * @return	string
	 */
	public function makeAbsolutePath($dir) {
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'EXT:'))	{
			list($extKey, $script) = explode('/', substr($dir, 4), 2);
			if ($extKey && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extKey)) {
				$extPath=\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);
				return substr($extPath, strlen(PATH_site)) . $script;
			}
		} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'FILE:')) {
				return substr($dir, 5);
		} else {
			return $dir;
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_base.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/beautyofcode/lib/class.tx_beautyofcode_d.php']);
}
?>
<?php
namespace TYPO3\Beautyofcode\Utility;

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
 * @author Felix Nagel <info@felixnagel.com>
 * @package	\TYPO3\Beautyofcode\Utility
 */
class GeneralUtility {

	/**
	 * Resolves a path prefixed with FILE: and EXT:
	 *
	 * If the path can successfully be resolved to an internal (relative to PATH_site)
	 * path, the PATH_site part is removed and the resulting path is returned.
	 * If its an external path, the input parameter is returned unchanged.
	 *
	 * @param string path to directory
	 * @return string
	 */
	public static function makeAbsolutePath($dir) {
		$absolutePath = '';

		$isExtensionNotation = \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'EXT:');
		$isFileNotation = \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'FILE:');

		if ($isFileNotation) {
			$dir = substr($dir, 5);
		}

		if ($isExtensionNotation || $isFileNotation) {
			$absolutePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($dir, TRUE, FALSE);
			$absolutePath = is_null($absolutePath) ? '' : substr($absolutePath, strlen(PATH_site));
		} else if (FALSE !== parse_url($dir)) {
			$absolutePath = $dir;
		}

		return $absolutePath;
	}
}
?>
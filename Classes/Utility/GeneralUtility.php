<?php
namespace TYPO3\Beautyofcode\Utility;

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
	 * @param string $dir Path to directory
	 *
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
		} elseif (FALSE !== parse_url($dir)) {
			$absolutePath = $dir;
		}

		return $absolutePath;
	}
}

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
	 * Function which resolves a path prefixed with FILE: and EXT:
	 *
	 * @param string path to directory
	 * @return string
	 */
	public static function makeAbsolutePath($dir) {
		$absolutePath = '';

		$isExtensionNotation = \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'EXT:');
		$isFileNotation = \TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'FILE:');

		if ($isExtensionNotation) {
			$absolutePath = self::getAbsolutePathFromExtensionNotation($dir);
		} elseif ($isFileNotation) {
			$absolutePath = substr($dir, 5);
		} else {
			$absolutePath = $dir;
		}

		return $absolutePath;
	}

	/**
	 * Returns an absolute path from EXT: notation from given $dir string
	 *
	 * @param string $dir
	 * @throws \BadFunctionCallException If the extension is not loaded, because then the file cannot be found/loaded.
	 * @return string
	 */
	protected static function getAbsolutePathFromExtensionNotation($dir) {
		list($extKey, $script) = explode('/', substr($dir, 4), 2);

		if ($extKey && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extKey)) {
			$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);

			$absolutePath = substr($extPath, strlen(PATH_site)) . $script;
		} else {
			$msg = sprintf(
				'\TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolute(): You specified an extension path (%s), but the extension %s is not loaded!',
				$dir,
				$extKey
			);

			throw new \BadFunctionCallException($msg, 1385231536);
		}

		return $absolutePath;
	}
}
?>
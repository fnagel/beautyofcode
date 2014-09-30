<?php
namespace TYPO3\Beautyofcode\Utility;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Provides a simple utility for finding files
 *
 * @package \TYPO3\Beautyofcode\Utility
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class FileFinderUtility {

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $excludePattern;

	/**
	 * Defines the starting point of the file search
	 *
	 * @param string $path
	 * @return $this
	 */
	public function in($path) {
		$this->path = $path;

		return $this;
	}

	/**
	 * Absolutizes the already defined search path
	 *
	 * @return $this
	 */
	public function absolutize() {
		$this->path = GeneralUtility::getFileAbsFileName($this->path);

		return $this;
	}

	/**
	 * Sets the exclude pattern
	 *
	 * @param string $pattern
	 * @return $this
	 */
	public function exclude($pattern) {
		$this->excludePattern = $pattern;

		return $this;
	}

	/**
	 * Starts the file search
	 *
	 * @param string $extensions
	 * @return array
	 */
	public function find($extensions = '') {
		$files = GeneralUtility::getFilesInDir(
			$this->path,
			$extensions,
			FALSE,
			'',
			$this->excludePattern
		);

		if (!is_array($files)) {
			$files = array();
		}

		return $files;
	}
}
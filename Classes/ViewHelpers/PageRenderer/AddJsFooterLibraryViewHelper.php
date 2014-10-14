<?php
namespace TYPO3\Beautyofcode\ViewHelpers\PageRenderer;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
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
 * Adds javascript libraries to the page footer
 *
 * @package \TYPO3\Beautyofcode\ViewHelpers\PageRenderer
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class AddJsFooterLibraryViewHelper extends \TYPO3\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper {

	/**
	 * Adds the incoming file to the page renderer javascript footer library stack.
	 *
	 * @param string $name
	 * @param string $file
	 * @param string $type
	 * @param bool $compress
	 * @param bool $forceOnTop
	 * @param string $allWrap
	 * @param bool $excludeFromConcatenation
	 * @return NULL
	 */
	public function render(
		$name,
		$file,
		$type = 'text/javascript',
		$compress = FALSE,
		$forceOnTop = FALSE,
		$allWrap = '',
		$excludeFromConcatenation = FALSE
	) {
		if ($this->isAccessiblePath($file)) {
			$this->pageRenderer->addJsFooterLibrary(
				$name,
				$this->fe->tmpl->getFileName($file),
				$type,
				$compress,
				$forceOnTop,
				$allWrap,
				$excludeFromConcatenation
			);
		}

		return NULL;
	}
}
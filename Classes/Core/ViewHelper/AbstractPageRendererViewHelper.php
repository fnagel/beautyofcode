<?php
namespace TYPO3\Beautyofcode\Core\ViewHelper;

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

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Abstract page renderer based view helper
 *
 * @package \TYPO3\Beautyofcode\Core\ViewHelper
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
abstract class AbstractPageRendererViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 *
	 * @var \TYPO3\CMS\Core\Page\PageRenderer
	 */
	protected $pageRenderer;

	/**
	 *
	 * @var TypoScriptFrontendController
	 */
	protected $fe;

	/**
	 * injectPageRenderer
	 *
	 * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
	 * @return void
	 */
	public function injectPageRenderer(\TYPO3\CMS\Core\Page\PageRenderer $pageRenderer) {
		$this->pageRenderer = $pageRenderer;
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->fe = $GLOBALS['TSFE'];
	}

	/**
	 * Flags if the given path is accessible.
	 *
	 * Returns true if the given $path is deteced as a remote path
	 * or is a local file and is accessible.
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function isAccessiblePath($path) {
		return $this->isRemotePath($path) || $this->isLocalPath($path);
	}

	/**
	 * Flags if the given path is a remote path.
	 *
	 * The check is very basic and done by using parse_url and checking
	 * if `scheme` or `host` keys are set in the path info array.
	 *
	 * @param string $path
	 * @return bool
	 */
	private function isRemotePath($path) {
		$host = parse_url($path, PHP_URL_HOST);

		return $host !== NULL;
	}

	/**
	 * Checks if the given path is locally accessible.
	 *
	 * @param string $path
	 * @return bool
	 */
	private function isLocalPath($path) {
		try {
			$pathInfo = new \SplFileInfo($path);

			$isLocalPath = $pathInfo->isFile() || $pathInfo->isLink() || $pathInfo->isReadable();

			unset($pathInfo);
		} catch (\Exception $e) {
			$isLocalPath = FALSE;
		}

		return $isLocalPath;
	}
}
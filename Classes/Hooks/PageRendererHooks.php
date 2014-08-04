<?php
namespace TYPO3\Beautyofcode\Hooks;

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

use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Various hooks for the PageRenderer
 *
 * @package \TYPO3\Beautyofcode\Hooks
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class PageRendererHooks {

	/**
	 *
	 * @var string
	 */
	const BRUSH_AUTOLOADER_LIST_ITEM_FORMAT = '%leadingCommaForNonZeroBasedIndices%\'%brush%	%brushResource%\'';

	/**
	 *
	 * @var string
	 */
	const AUTOLOADER_MARKER = '###AUTOLOADER_LIST###';

	/**
	 *
	 * @var PhpFrontend
	 */
	protected $cache;

	/**
	 *
	 * @var TypoScriptFrontendController
	 */
	protected $fe;

	/**
	 * injectCacheManager
	 *
	 * @param CacheManager $cacheManager
	 * @return void
	 */
	public function injectCacheManager(CacheManager $cacheManager = NULL) {
		if (NULL === $cacheManager) {
			$cacheManager = $GLOBALS['typo3CacheManager'];
		}

		$this->cache = $cacheManager->getCache('cache_beautyofcode');
	}

	/**
	 * injectTypoScriptFrontendController
	 *
	 * @param TypoScriptFrontendController $fe
	 * @return void
	 */
	public function injectTypoScriptFrontendController(
		TypoScriptFrontendController $fe = NULL
	) {
		if (NULL === $fe) {
			$fe = $GLOBALS['TSFE'];
		}

		$this->fe = $fe;
	}

	/**
	 * Adds the brush assets used on current page.
	 *
	 * The hook also takes care of the used syntax highlighting library.
	 *
	 * @param array &$pageRendererAssets @see PageRenderer::executePreRenderHook()
	 *                                   for a list of incoming assets
	 * @param PageRenderer &$pageRenderer
	 * @return void
	 */
	public function addBrushAssets(
		array &$pageRendererAssets,
		PageRenderer &$pageRenderer
	) {
		$this->injectCacheManager($this->cache);
		$this->injectTypoScriptFrontendController($this->fe);

		// SyntaxHighlighter is used
		if (TRUE === array_key_exists('boc_inline', $pageRendererAssets['jsInline'])) {
			$code = $this->addAutoloaderAssets($pageRendererAssets['jsInline']['boc_inline']['code']);

			$pageRendererAssets['jsInline']['boc_inline']['code'] = $code;
		}

		// Prism is used
// 		if (TRUE === array_key_exists('boc_[brushName]', $pageRendererAssets['jsLibs'])) {

// 		}
	}

	/**
	 * addAutoloaderAssets
	 *
	 * @param string $codeBlock
	 * @return string
	 */
	protected function addAutoloaderAssets($codeBlock) {
		$entryIdentifier = $this->fe->getHash();

		$brushes = array();
		if ($this->cache->has($entryIdentifier)) {
			$brushes = $this->cache->requireOnce($entryIdentifier);
		}

		$autoloaderString = '';

		foreach ($brushes as $brushIndex => $brush) {
			$autoloaderString .= $this->getAutoloaderStringForBrush($brushIndex, $brush);
		}

		return str_replace(self::AUTOLOADER_MARKER, $autoloaderString, $codeBlock);
	}

	/**
	 * getAutoloaderStringForBrush
	 *
	 * @param integer $brushIndex
	 * @param string $brush
	 * @return string
	 */
	protected function getAutoloaderStringForBrush($brushIndex, $brush) {
		return strtr(self::BRUSH_AUTOLOADER_LIST_ITEM_FORMAT, array(
			'%leadingCommaForNonZeroBasedIndices%' => $brushIndex > 0 ? ',' : '',
			'%brush%' => $brush,
			'%brushResource%' => $brush,
		));
	}
}
?>
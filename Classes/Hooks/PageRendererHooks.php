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

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\Beautyofcode\Service\BrushRegistryService;

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
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 *
	 * @var ConfigurationInterface
	 */
	protected $highlighterConfiguration;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\BrushRegistryService
	 */
	protected $brushRegistryService;

	/**
	 *
	 * @var TypoScriptFrontendController
	 */
	protected $fe;

	/**
	 * injectObjectManager
	 *
	 * @param ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(
		ObjectManagerInterface $objectManager = NULL
	) {
		if (is_null($objectManager)) {
			$objectManager = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Extbase\\Object\\ObjectManager'
			);
		}

		$this->objectManager = $objectManager;
	}

	/**
	 * injectHighlighterConfiguration
	 *
	 * @param ConfigurationInterface $highlighterConfiguration
	 * @return void
	 */
	public function injectHighlighterConfiguration(
		ConfigurationInterface $highlighterConfiguration = NULL
	) {
		if (is_null($highlighterConfiguration)) {
			$highlighterConfiguration = $this->objectManager->get(
				'TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface'
			);
		}

		$this->highlighterConfiguration = $highlighterConfiguration;
	}

	/**
	 * injectBrushRegistryService
	 *
	 * @param BrushRegistryService $brushRegistryService
	 * @return void
	 */
	public function injectBrushRegistryService(
		BrushRegistryService $brushRegistryService = NULL
	) {
		if (is_null($brushRegistryService)) {
			$brushRegistryService = $this->objectManager->get(
				'TYPO3\\Beautyofcode\\Service\BrushRegistryService'
			);
		}

		$this->brushRegistryService = $brushRegistryService;
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->injectObjectManager($this->objectManager);
		$this->injectHighlighterConfiguration($this->highlighterConfiguration);
		$this->injectBrushRegistryService($this->brushRegistryService);
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
		$this->initializeObject();

		$brushes = $this->brushRegistryService->getBrushes();
		$this->highlighterConfiguration->addRegisteredBrushes($brushes);
	}
}
?>
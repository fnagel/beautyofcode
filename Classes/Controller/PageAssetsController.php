<?php
namespace TYPO3\Beautyofcode\Controller;

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

use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\Beautyofcode\Service\BrushRegistryService;

/**
 * PageAssetsController
 *
 * @category Category
 * @package \TYPO3\Beautyofcode\Controller
 * @subpackage Subpackage
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class PageAssetsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	 * injectHighlighterConfiguration
	 *
	 * @param ConfigurationInterface $highlighterConfiguration
	 * @return void
	 */
	public function injectHighlighterConfiguration(
		ConfigurationInterface $highlighterConfiguration
	) {
		$this->highlighterConfiguration = $highlighterConfiguration;
	}

	/**
	 * injectBrushRegistryService
	 *
	 * @param BrushRegistryService $brushRegistryService
	 * @return void
	 */
	public function injectBrushRegistryService(
		BrushRegistryService $brushRegistryService
	) {
		$this->brushRegistryService = $brushRegistryService;
	}

	/**
	 * addAction
	 *
	 * @return void
	 */
	public function addAction() {
		$registeredBrushes = $this->brushRegistryService->getBrushes();
		$brushes = $this->highlighterConfiguration->prepareRegisteredBrushes($registeredBrushes);

		$this->view->assign('brushes', $brushes);
	}
}
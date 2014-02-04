<?php
namespace TYPO3\Beautyofcode\Configuration\Flexform;

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
 * Function to add select options dynamically (loaded in flexform)
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Configuration\Flexform
 */
class LanguageItems {

	/**
	 *
	 * @var integer
	 */
	protected $contentElementPid;

	/**
	 *
	 * @var \TYPO3\CMS\Frontend\Page\PageRepository
	 */
	protected $pageRepository;

	/**
	 *
	 * @var \TYPO3\CMS\Core\TypoScript\TemplateService
	 */
	protected $templateService;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	protected $brushDiscoveryService;

	/**
	 * Injects the page repository
	 *
	 * @param \TYPO3\CMS\Frontend\Page\PageRepository $pageRepository
	 * @return void
	 */
	public function injectPageRepository(\TYPO3\CMS\Frontend\Page\PageRepository $pageRepository = NULL) {
		if (TRUE === is_null($pageRepository)) {
			$this->pageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
		} else {
			$this->pageRepository = $pageRepository;
		}
	}

	/**
	 * Injects the template service
	 *
	 * @param \TYPO3\CMS\Core\TypoScript\TemplateService $templateService
	 * @return void
	 */
	public function injectTemplateService(\TYPO3\CMS\Core\TypoScript\TemplateService $templateService = NULL) {
		if (TRUE === is_null($templateService)) {
			$this->templateService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
		} else {
			$this->templateService = $templateService;
		}
	}

	/**
	 *
	 * @param \TYPO3\Beautyofcode\Service\BrushDiscoveryService $brushDiscoveryService
	 * @return void
	 */
	public function injectBrushDiscoveryService(\TYPO3\Beautyofcode\Service\BrushDiscoveryService $brushDiscoveryService = NULL) {
		if (NULL === $brushDiscoveryService) {
			$this->brushDiscoveryService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService');
		} else {
			$this->brushDiscoveryService = $brushDiscoveryService;
		}
	}

	/**
	 * Initializes the object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->injectPageRepository($this->pageRepository);
		$this->injectTemplateService($this->templateService);
		$this->injectBrushDiscoveryService($this->brushDiscoveryService);
	}

	/**
	 * This function is called from the flexform and
	 * adds avaiable programming languages to the select options
	 *
	 * @param array flexform data
	 * @return array
	 */
	public function getConfiguredLanguages($config) {
		$this->initializeObject();

		static $cachedFields = 0;

		if ($cachedFields != 0) {
			$config['items'] = $cachedFields;
		} else {

			// make brushes list to flexform selectbox item array
			$optionList = array();

			$this->contentElementPid = $config['row']['pid'];

			$brushesArray = $this->getUniqueAndSortedBrushes();

			foreach ($brushesArray as $brushName => $brushLabel) {
				$optionList[] = array($brushLabel, $brushName);
			}

			$config['items'] = $optionList;
		}

		$cachedFields = $config['items'];

		return $config;
	}

	/**
	 * Returns unique and sorted brushes
	 *
	 * @return array
	 */
	protected function getUniqueAndSortedBrushes() {
		$configArray = $this->getConfig();

		$brushes = $this->brushDiscoveryService->discoverBrushes();

		return $brushes[$configArray['library']];
	}

	/**
	 * Generates TS Config of the plugin
	 *
	 * @return array
	 */
	protected function getConfig() {
		$this->pageRepository->init(TRUE);

		$this->templateService->init();

		// Avoid an error
		$this->templateService->tt_track = 0;

		// Get rootline for current PID
		$rootline = $this->pageRepository->getRootLine($this->contentElementPid);

		// Start TS template
		$this->templateService->start($rootline);

		// Generate TS config
		$this->templateService->generateConfig();

		return $this->templateService->setup['plugin.']['tx_beautyofcode.']['settings.'];
	}
}
?>
<?php
namespace TYPO3\Beautyofcode\Configuration\Flexform;

/***************************************************************
 * Copyright notice
 *
 * (c) 2010-2015 Felix Nagel (info@felixnagel.com)
 * (c) 2013-2015 Thomas Juhnke <typo3@van-tomas.de>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Function to add select options dynamically (loaded in flexform)
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\Configuration\Flexform
 */
class LanguageItems {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

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
	 * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $highlighterConfiguration;

	/**
	 * Page uid (PID) for TypoScript generation
	 *
	 * Fallback to root PID (0)
	 *
	 * @var integer
	 */
	protected $contentElementPid = 0;

	/**
	 * injectObjectManager
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager = NULL) {
		if (is_null($objectManager)) {
			$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		}

		$this->objectManager = $objectManager;
	}

	/**
	 * Injects the page repository
	 *
	 * @param \TYPO3\CMS\Frontend\Page\PageRepository $pageRepository
	 * @return void
	 */
	public function injectPageRepository(
		\TYPO3\CMS\Frontend\Page\PageRepository $pageRepository = NULL
	) {
		if (is_null($pageRepository)) {
			$pageRepository = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Frontend\\Page\\PageRepository'
			);
		}

		$this->pageRepository = $pageRepository;
	}

	/**
	 * Injects the template service
	 *
	 * @param \TYPO3\CMS\Core\TypoScript\TemplateService $templateService
	 * @return void
	 */
	public function injectTemplateService(
		\TYPO3\CMS\Core\TypoScript\TemplateService $templateService = NULL
	) {
		if (is_null($templateService)) {
			$templateService = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Core\\TypoScript\\TemplateService'
			);
		}

		$this->templateService = $templateService;
	}

	/**
	 * initialize
	 *
	 * @return void
	 */
	public function initialize() {
		$this->injectObjectManager($this->objectManager);
		$this->injectPageRepository($this->pageRepository);
		$this->injectTemplateService($this->templateService);

		$this->highlighterConfiguration = $this->objectManager->get(
			'TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface'
		);
	}

	/**
	 * This function is called from the flexform and
	 * adds avaiable programming languages to the select options
	 *
	 * @param array $config flexform data
	 * @return array
	 */
	public function getConfiguredLanguages($config) {
		$this->initialize();

		static $cachedFields = 0;

		if ($cachedFields != 0) {
			$config['items'] = $cachedFields;
		} else {

			// make brushes list to flexform selectbox item array
			$optionList = array();

			if (isset($config['row']['pid']) && is_numeric($config['row']['pid'])) {
				$this->contentElementPid = (int) $config['row']['pid'];
			}

			if ($this->contentElementPid === 0 && isset($config['row']['uid']) && is_numeric($config['row']['uid'])) {
				$this->contentElementPid = $this->getPageUidByRecordUid($config['row']['uid']);
			}

			$brushesArray = $this->getUniqueAndSortedBrushes();

			foreach ($brushesArray as $i => $brush) {
				if (strtolower($brush) === 'plain') {
					continue;
				}
				// skip unknown brushes
				if (!$this->highlighterConfiguration->hasBrushIdentifier($brush)) {
					continue;
				}

				$optionList[$i] = array_reverse(
					$this->highlighterConfiguration->getBrushIdentifierAliasAndLabel($brush)
				);
			}

			$config['items'] = array_merge($config['items'], $optionList);
		}
		$cachedFields = $config['items'];

		return $config;
	}

	/**
	 * Returns the page uid by given record uid
	 *
	 * @param int $recordUid
	 *
	 * @return int
	 */
	private function getPageUidByRecordUid($recordUid) {
		$recordPid = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
			'pid', 'tt_content', 'uid = ' . $recordUid
		);

		return (int) $recordPid['pid'];
	}

	/**
	 * Returns the global DatabaseConnection instance
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	private function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Returns unique and sorted brushes
	 *
	 * @return array
	 */
	protected function getUniqueAndSortedBrushes() {
		$configArray = $this->getConfig();

		$brushesArray = GeneralUtility::trimExplode(
			',',
			$configArray['brushes'],
			TRUE
		);

		// make unique
		foreach ($brushesArray as &$value) {
			$value = serialize($value);
		}

		$brushesArray = array_unique($brushesArray);

		foreach ($brushesArray as &$value) {
			$value = unserialize($value);
		}

		// sort a-z
		sort($brushesArray);

		return $brushesArray;
	}

	/**
	 * Generates TS Config of the plugin
	 *
	 * @return array
	 */
	protected function getConfig() {
		// create dummy TSFE for TemplateService
		$GLOBALS['TSFE'] = new \stdClass();

		$this->pageRepository->init(TRUE);

		$this->templateService->init();

		// Avoid an error
		$this->templateService->tt_track = 0;

		// Get rootline for current PID
		$rootline = $this
			->pageRepository
			->getRootLine(
				$this->contentElementPid
			);

		// Start TS template
		$this->templateService->start($rootline);

		// Generate TS config
		$this->templateService->generateConfig();

		return $this
			->templateService
			->setup['plugin.']['tx_beautyofcode.']['settings.'];
	}
}
?>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Function to add select options dynamically (loaded in flexform)
 *
 * @author Felix Nagel <info@felixnagel.com>
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
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	protected $brushDiscoveryService;

	/**
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * injectObjectManager
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(
		\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager = NULL
	) {
		// @codeCoverageIgnoreStart
		if (is_null($objectManager)) {
			$objectManager = GeneralUtility::makeInstance(
				'TYPO3\\CMS\\Extbase\\Object\\ObjectManager'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->objectManager = $objectManager;
	}

	/**
	 * injectConfigurationManager
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(
		\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager = NULL
	) {
		// @codeCoverageIgnoreStart
		if (is_null($configurationManager)) {
			$configurationManager = $this->objectManager->get(
				'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->configurationManager = $configurationManager;
	}

	/**
	 * injectBrushDiscoveryService
	 *
	 * @param \TYPO3\Beautyofcode\Service\BrushDiscoveryService $brushDiscoveryService
	 * @return void
	 */
	public function injectBrushDiscoveryService(
		\TYPO3\Beautyofcode\Service\BrushDiscoveryService $brushDiscoveryService = NULL
	) {
		// @codeCoverageIgnoreStart
		if (is_null($brushDiscoveryService)) {
			$brushDiscoveryService = $this->objectManager->get(
				'TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->brushDiscoveryService = $brushDiscoveryService;
	}

	/**
	 * Initializes the object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->injectObjectManager($this->objectManager);
		$this->injectConfigurationManager($this->configurationManager);
		$this->injectBrushDiscoveryService($this->brushDiscoveryService);

		$configuration = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = ArrayUtility::getValueByPath(
			$configuration, 'plugin./tx_beautyofcode./settings.'
		);
	}

	/**
	 * This function is called from the flexform and
	 * adds avaiable programming languages to the select options
	 *
	 * @param array $config Flexform data
	 * @param \TYPO3\CMS\Backend\Form\FormEngine $formEngine
	 * @return array
	 */
	public function getDiscoveredBrushes(
		$config,
		\TYPO3\CMS\Backend\Form\FormEngine $formEngine
	) {
		$this->initializeObject();

		static $cachedFields = 0;

		$tceFormItemLabelValueArray = array();

		if ($cachedFields != 0) {
			$config['items'] = $cachedFields;
		} else {
			$brushesArray = $this->getBrushes();

			foreach ($brushesArray as $brushName => $brushLabel) {
				$tceFormItemLabelValueArray[] = array($brushLabel, $brushName);
			}

			$config['items'] = $tceFormItemLabelValueArray;
		}

		$cachedFields = $config['items'];

		return $config;
	}

	/**
	 * Returns unique and sorted brushes
	 *
	 * @return array
	 */
	protected function getBrushes() {
		$brushes = $this->brushDiscoveryService->discoverBrushes();

		return $brushes[$this->settings['library']];
	}
}
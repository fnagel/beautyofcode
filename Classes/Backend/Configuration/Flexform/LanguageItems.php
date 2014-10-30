<?php
namespace TYPO3\Beautyofcode\Backend\Configuration\Flexform;

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

/**
 * Function to add select options dynamically (loaded in flexform)
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Backend\Configuration\Flexform
 */
class LanguageItems {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\BrushDiscovery
	 */
	protected $brushDiscovery;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $highlighterConfiguration;

	/**
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Holds the items from an earlier run of the instance
	 *
	 * @var array
	 */
	protected $items;

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
	 * injectBrushDiscovery
	 *
	 * @param \TYPO3\Beautyofcode\Highlighter\BrushDiscovery $brushDiscovery
	 * @return void
	 */
	public function injectBrushDiscovery(
		\TYPO3\Beautyofcode\Highlighter\BrushDiscovery $brushDiscovery = NULL
	) {
		// @codeCoverageIgnoreStart
		if (is_null($brushDiscovery)) {
			$brushDiscovery = $this->objectManager->get(
				'TYPO3\\Beautyofcode\\Highlighter\\BrushDiscovery'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->brushDiscovery = $brushDiscovery;
	}

	/**
	 * injectHighlighterConfiguration
	 *
	 * @param \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface $configuration
	 * @return void
	 */
	public function injectHighlighterConfiguration(
		\TYPO3\Beautyofcode\Highlighter\ConfigurationInterface $configuration = NULL
	) {
		// @codeCoverageIgnoreStart
		if (is_null($configuration)) {
			$configuration = $this->objectManager->get(
				'TYPO3\\Beautyofcode\\Highlighter\\ConfigurationInterface'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->highlighterConfiguration = $configuration;
	}

	/**
	 * Initializes the object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->injectObjectManager($this->objectManager);
		$this->injectBrushDiscovery($this->brushDiscovery);
		$this->injectHighlighterConfiguration($this->highlighterConfiguration);
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

		$tceFormItemLabelValueArray = array();

		if (!is_null($this->items)) {
			$config['items'] = $this->items;

			return $config;
		}

		$brushesArray = $this->brushDiscovery->getBrushes($this->highlighterConfiguration);

		foreach ($brushesArray as $brushIdentifier => $brushLabel) {
			$brushAlias = $this->highlighterConfiguration->getBrushAliasByIdentifier($brushIdentifier);
			$tceFormItemLabelValueArray[] = array($brushLabel, $brushAlias);
		}

		$config['items'] = $tceFormItemLabelValueArray;

		$this->items = $config['items'];

		return $config;
	}
}
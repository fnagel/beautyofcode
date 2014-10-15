<?php
namespace TYPO3\Beautyofcode\Highlighter;

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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Configuration
 *
 * @package \TYPO3\Beautyofcode\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class Configuration implements ConfigurationInterface {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 *
	 * @var array
	 */
	private $identifierAliases = array();

	/**
	 *
	 * @var array
	 */
	private $failsafeAliases = array();

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $configuration;


	/**
	 * injectObjectManager
	 *
	 * @param ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * injectConfiguration
	 *
	 * @param ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfiguration(ConfigurationManagerInterface $configurationManager) {
		$configuration = $configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = ArrayUtility::getValueByPath($configuration, 'plugin./tx_beautyofcode./settings.');
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->initializeBrushMaps();

		$this->configuration = $this->objectManager->get(
			'TYPO3\\Beautyofcode\\Highlighter\\Configuration\\' . $this->settings['library'],
			$this->identifierAliases,
			$this->failsafeAliases
		);
	}

	/**
	 * Initializes the identifier-to-alias and failsafe alias maps.
	 *
	 * @return void
	 * @throws \RuntimeException
	 */
	protected function initializeBrushMaps() {
		$this->identifierAliases = ArrayUtility::getValueByPath(
			$GLOBALS,
			'TYPO3_CONF_VARS/EXTCONF/beautyofcode/IdentifierAliases/' . $this->settings['library']
		);
		$this->failsafeAliases = ArrayUtility::getValueByPath(
			$GLOBALS,
			'TYPO3_CONF_VARS/EXTCONF/beautyofcode/FailsafeAliases/' . $this->settings['library']
		);
	}


	/**
	 * getFailSafeBrushAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getFailSafeBrushAlias($brushAlias) {
		return $this->configuration->getFailSafeBrushAlias($brushAlias);
	}

	/**
	 * getClassAttributeString
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform
	 * @return string
	 */
	public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform) {
		return $this->configuration->getClassAttributeString($flexform);
	}

	/**
	 * getBrushAliasByIdentifier
	 *
	 * @param string $brushIdentifier
	 * @return string
	 */
	public function getBrushAliasByIdentifier($brushIdentifier) {
		return $this->configuration->getBrushAliasByIdentifier($brushIdentifier);
	}

	/**
	 * getBrushIdentifierByAlias
	 *
	 * @param string $brushAlias
	 * @return string
	 */
	public function getBrushIdentifierByAlias($brushAlias) {
		return $this->configuration->getBrushIdentifierByAlias($brushAlias);
	}

	/**
	 * Flags if the active highlighter configuraiton has static brushes configured.
	 *
	 * @param array $settings
	 * @return bool
	 */
	public function hasStaticBrushes(array $settings = array()) {
		return $this->configuration->hasStaticBrushes(array_merge($this->settings, $settings));
	}

	/**
	 * Returns the static brushes array, with added `plain` brush if not configured
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getStaticBrushesWithPlainFallback(array $settings = array()) {
		return $this->configuration->getStaticBrushesWithPlainFallback(array_merge($this->settings, $settings));
	}
}
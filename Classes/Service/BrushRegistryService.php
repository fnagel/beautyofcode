<?php
namespace TYPO3\Beautyofcode\Service;

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

use TYPO3\Beautyofcode\Domain\Model\ContentElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * This service allows the storage of brushes into the system registry
 *
 * @package \TYPO3\Beautyofcode\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushRegistryService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 *
	 * @var BrushDiscoveryService
	 */
	protected $brushDiscoveryService;

	/**
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 *
	 * @var array
	 */
	protected $dependencies;

	/**
	 *
	 * @var array
	 */
	protected $brushes = array();

	/**
	 * injectConfiguratioManager
	 *
	 * @param ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
		$configuration = $configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);

		$this->settings = ArrayUtility::getValueByPath(
			$configuration,
			'plugin./tx_beautyofcode./settings.'
		);
	}

	/**
	 * injectBrushDiscoveryService
	 *
	 * @param BrushDiscoveryService $brushDiscoveryService
	 * @return void
	 */
	public function injectBrushDiscoveryService(
		BrushDiscoveryService $brushDiscoveryService
	) {
		$this->brushDiscoveryService = $brushDiscoveryService;
		$dependencies = $this->brushDiscoveryService->discoverDependencies();

		$this->dependencies = $dependencies[$this->settings['library']];
	}

	/**
	 * registerBrush
	 *
	 * @param ContentElement $contentElement
	 * @return void
	 */
	public function registerBrush(ContentElement $contentElement) {
		$brush = $contentElement->getFlexformObject()->getLanguage();

		if (FALSE === in_array($brush, $this->brushes)) {
			$this->brushes[] = $brush;
		}

		while (isset($this->dependencies[$brush])) {
			$brush = $this->dependencies[$brush];

			if (in_array($brush, $this->brushes)) {
				continue;
			}

			array_unshift($this->brushes, $brush);
		}
	}

	/**
	 * getBrushes
	 *
	 * @return array
	 */
	public function getBrushes() {
		return $this->brushes;
	}
}
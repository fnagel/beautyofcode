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

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\Beautyofcode\Highlighter\BrushDiscovery;
use TYPO3\Beautyofcode\Highlighter\BrushRegistry;
use TYPO3\Beautyofcode\Domain\Model\ContentElement;

/**
 * Glues the Highlighter configuration, registry and discovery together.
 *
 * @package \TYPO3\Beautyofcode\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class HighlighterService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var ConfigurationInterface
	 */
	protected $configuration;

	/**
	 * @var BrushDiscovery
	 */
	protected $discovery;

	/**
	 * @var BrushRegistry
	 */
	protected $registry;

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
	 * @param ConfigurationInterface $configuration
	 * @return void
	 */
	public function injectConfiguration(ConfigurationInterface $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * injectDiscovery
	 *
	 * @param BrushDiscovery $discovery
	 * @return void
	 */
	public function injectDiscovery(BrushDiscovery $discovery) {
		$this->discovery = $discovery;
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->registry = $this->objectManager->get(
			'TYPO3\\Beautyofcode\\Highlighter\\BrushRegistry',
			$this->configuration,
			$this->discovery->getDependencies($this->configuration->getLibraryName())
		);
	}

	/**
	 * getDiscoveredBrushes
	 *
	 * @return array
	 */
	public function getDiscoveredBrushes() {
		return $this->discovery->getBrushes($this->configuration->getLibraryName());
	}

	/**
	 * getDiscoveredDependencies
	 *
	 * @return array
	 */
	public function getDiscoveredDependencies() {
		return $this->discovery->getDependencies($this->configuration->getLibraryName());
	}

	/**
	 * registerBrushAlias
	 *
	 * @param ContentElement $contentElement
	 * @return void
	 */
	public function registerBrushAlias(ContentElement $contentElement) {
		$brushAlias = $this->configuration->getFailSafeBrushAlias(
			$contentElement->getFlexformObject()->getCLang()
		);
		$contentElement->getFlexformObject()->setCLang($brushAlias);

		$this->registry->add($brushAlias);
	}

	/**
	 * getRegisteredBrushes
	 *
	 * @return \ArrayIterator
	 */
	public function getRegisteredBrushes() {
		return $this->registry->getIterator();
	}

	/**
	 * generateClassAttributeString
	 *
	 * @param ContentElement $contentElement
	 * @return void
	 */
	public function generateClassAttributeString(ContentElement $contentElement) {
		$contentElement->getFlexformObject()->setClassAttributeString(
			$this->configuration->getClassAttributeString($contentElement->getFlexformObject())
		);
	}
}
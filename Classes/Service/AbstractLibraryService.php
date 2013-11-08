<?php
namespace FNagel\Beautyofcode\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Class short description
 *
 * Class long description
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
abstract class AbstractLibraryService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 *
	 * @var \TYPO3\CMS\Core\Cache\CacheManager
	 */
	protected $cacheManager;

	/**
	 *
	 * @var \FNagel\Beautyofcode\Utility\GeneralUtility
	 */
	protected $bocGeneralUtility;

	/**
	 *
	 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected $typoscriptFrontendController;

	/**
	 *
	 * @var \TYPO3\CMS\Core\Page\PageRenderer
	 */
	protected $pageRenderer;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * The concrete configuration, extracted from the configuration manager
	 *
	 * @var array
	 */
	protected $configuration;

	/**
	 * Injects the object manager
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 *
	 * @param \TYPO3\CMS\Core\Cache\CacheManager $cacheManager
	 */
	public function injectCacheManager(\TYPO3\CMS\Core\Cache\CacheManager $cacheManager) {
		$this->cacheManager = $cacheManager;
	}

	/**
	 *
	 * @param \FNagel\Beautyofcode\Utility\GeneralUtility $generalUtility
	 */
	public function injectBeautyofcodeGeneralUtility(\FNagel\Beautyofcode\Utility\GeneralUtility $generalUtility) {
		$this->bocGeneralUtility = $generalUtility;
	}

	public function initializeObject() {
		$this->uriBuilder = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');

		$this->typoscriptFrontendController = $GLOBALS['TSFE'];

		$this->pageRenderer = $this->typoscriptFrontendController->getPageRenderer();
	}

	/**
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 */
	public function setConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * Extracts and stores the concrete library configuration
	 *
	 * @return void
	 */
	abstract public function configure();

	/**
	 * adds the necessary libraries to the page renderer
	 *
	 * @return void
	 */
	abstract public function load();
}
?>
<?php
namespace TYPO3\Beautyofcode\Service;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Abstract library service
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
abstract class AbstractLibraryService {

	/**
	 *
	 * @var string
	 */
	protected $layoutRootPath = 'typo3conf/ext/beautyofcode/Resources/Private/Layouts/';

	/**
	 *
	 * @var string
	 */
	protected $partialRootPath = 'typo3conf/ext/beautyofcode/Resources/Private/Partials/';

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
	 * @var \TYPO3\Beautyofcode\Utility\GeneralUtility
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
	 * A list of keys which are invalid or unavailable in the concrete asset service
	 *
	 * @var array
	 */
	protected $classAttributeConfigurationSkipKeys = array();

	/**
	 * A list of values which should be skipped in the concrete asset service
	 *
	 * @var array
	 */
	protected $classAttributeConfigurationSkipValues = array('', 'auto');

	/**
	 *
	 * @var array
	 */
	protected $classAttributeConfigurationStack = array();

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
	 * @param \TYPO3\Beautyofcode\Utility\GeneralUtility $generalUtility
	 */
	public function injectBeautyofcodeGeneralUtility(\TYPO3\Beautyofcode\Utility\GeneralUtility $generalUtility) {
		$this->bocGeneralUtility = $generalUtility;
	}

	/**
	 *
	 * @return void
	 */
	public function initializeObject() {
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
	public function configure() {
		$_configuration = $this
			->configurationManager
			->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);

		$commonConfiguration = $_configuration['common'];
		// we also could pull that from the class name, but this is more pragmatic...
		$version = $_configuration['library'];
		$versionConfiguration = $_configuration[$version];

		$this->configuration = array_merge($commonConfiguration, $versionConfiguration);
	}

	/**
	 *
	 * @param array $additionalTemplateVariables
	 * @return void
	 */
	protected function addInlineJavascript($additionalTemplateVariables = array()) {
		$templateVariables = array_merge(
			array(
				'settings' => $this->configuration
			),
			$additionalTemplateVariables
		);

		$cacheId = md5(serialize($templateVariables));

		if ($this->cacheManager->getCache('cache_beautyofcode')->has($cacheId)) {
			$resource = $this->cacheManager->getCache('cache_beautyofcode')->get($cacheId);
		} else {
			$resource = $this->renderInlineJavascript($templateVariables);

			$this->cacheManager
				->getCache('cache_beautyofcode')
				->set($cacheId, $resource, array(), 0);
		}

		$this->pageRenderer->addJsFooterInlineCode('beautyofcode_inline', $resource);
	}

	/**
	 *
	 * @param array $templateVariables
	 * @return string
	 */
	protected function renderInlineJavascript($templateVariables = array()) {
		/* @var $view \TYPO3\CMS\Fluid\View\StandaloneView */
		$view = $this->objectManager->get(
			'TYPO3\\CMS\\Fluid\\View\\StandaloneView',
			$this->configurationManager->getContentObject()
		);

		$view->setFormat('html');
		$view->setLayoutRootPath($this->layoutRootPath);
		$view->setPartialRootPath($this->partialRootPath);
		$view->setTemplatePathAndFilename($this->templatePathAndFilename);

		$view->assignMultiple($templateVariables);

		return $view->render();
	}

	/**
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function pushClassAttributeConfiguration($key, $value) {
		$skipKey = in_array($key, $this->classAttributeConfigurationSkipKeys);
		$skipValue = in_array($value, $this->classAttributeConfigurationSkipValues);

		if (FALSE === $skipKey && FALSE === $skipValue) {
			$this->classAttributeConfigurationStack[$key] = $value;
		}
	}

	/**
	 * Adds the necessary libraries to the page renderer
	 *
	 * @return void
	 */
	abstract public function load();

	/**
	 * Returns the class attribute configuration string for a concrete library service
	 *
	 * @return string
	 */
	abstract public function getClassAttributeConfiguration();
}
?>
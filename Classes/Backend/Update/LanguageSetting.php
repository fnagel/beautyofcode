<?php
namespace TYPO3\Beautyofcode\Backend\Update;

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
use TYPO3\Beautyofcode\Service\BrushDiscoveryService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * The language setting update class
 *
 * @package \TYPO3\Beautyofcode\Backend\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 */
class LanguageSetting extends \TYPO3\Beautyofcode\Backend\Update\AbstractUpdate {

	/**
	 * Stack of all plugins on this TYPO3.CMS instance
	 *
	 * @var array
	 */
	protected $plugins = array();

	/**
	 *
	 * @var string
	 */
	protected $template = 'Update/LanguageSetting.html';

	/**
	 *
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	protected $brushDiscoveryService;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Highlighter\ConfigurationInterface
	 */
	protected $highlighterConfiguration;

	/**
	 *
	 * @var \TYPO3\CMS\Core\Configuration\Flexform\FlexformTools
	 */
	protected $flexformTools;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * injectObjectManager
	 *
	 * @param ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(ObjectManagerInterface $objectManager = NULL) {
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
	 * injectBrushDiscoveryService
	 *
	 * @param BrushDiscoveryService $brushDiscoveryService
	 * @return void
	 */
	public function injectBrushDiscoveryService(
		BrushDiscoveryService $brushDiscoveryService = NULL
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
	 * injectFlexformTools
	 *
	 * @param FlexFormTools $flexformTools
	 * @return void
	 */
	public function injectFlexformTools(FlexformTools $flexformTools = NULL) {
		// @codeCoverageIgnoreStart
		if (is_null($flexformTools)) {
			$flexformTools = $this->objectManager->get(
				'TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->flexformTools = $flexformTools;
	}

	/**
	 * injectConfigurationManager
	 *
	 * @param ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager = NULL) {
		// @codeCoverageIgnoreStart
		if (is_null($configurationManager)) {
			$configurationManager = $this->objectManager->get(
				'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'
			);
		}
		// @codeCoverageIgnoreEnd

		$this->configurationManager = $configurationManager;

		$configuration = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = ArrayUtility::getValueByPath(
			$configuration,
			'plugin./tx_beautyofcode./settings.'
		);
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->injectObjectManager($this->objectManager);
		$this->injectBrushDiscoveryService($this->brushDiscoveryService);
		$this->injectHighlighterConfiguration($this->highlighterConfiguration);
		$this->injectFlexformTools($this->flexformTools);
		$this->injectConfigurationManager($this->configurationManager);

		$this->plugins = $this->db->exec_SELECTquery(
			'uid, header, pi_flexform',
			'tt_content',
			sprintf(
				'list_type IN (%s)',
				'\'' . implode('\',\'', array('beautyofcode_pi1', 'beautyofcode_contentrenderer')) . '\''
			)
		);

		$this->view->setTemplatePathAndFilename($this->template);

		$this->expandFlexformData();
	}

	/**
	 * Expands the flexform data of all plugins within the plugin stack
	 *
	 * @return void
	 */
	protected function expandFlexformData() {
		$plugins = array();

		while (($plugin = $this->db->sql_fetch_assoc($this->plugins))) {
			$flexformData = GeneralUtility::xml2array($plugin['pi_flexform']);

			$plugin['pi_flexform'] = $flexformData;
			$plugin['header'] = $this->getPluginHeader($plugin);

			$plugins[] = $plugin;
		}

		$this->db->sql_free_result($this->plugins);

		$this->plugins = $plugins;
	}

	/**
	 * Returns the plugin header
	 *
	 * First looks up the flexform (cLabel), then header field
	 *
	 * @param array $plugin An associative array of the tt_content plugin record
	 * @return string
	 */
	protected function getPluginHeader($plugin) {
		$cLabel = ArrayUtility::getValueByPath(
			$plugin['pi_flexform'],
			'data/sDEF/lDEF/cLabel/vDEF'
		);

		if ('' !== $cLabel) {
			$title = $cLabel;
		} elseif ('' !== $plugin['header']) {
			$title = $plugin['header'];
		} else {
			$title = '(Untitled)';
		}

		return $title;
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Update\AbstractUpdate::getInformation()
	 */
	public function getInformation() {
		$this->view->assign('section', 'Information');

		$this->view->assign('plugins', $this->plugins);
		$this->view->assign('availableBrushes', $this->getAvailableBrushes());

		return $this->view->render();
	}

	/**
	 * Returns a list with all available brushes
	 *
	 * @return array
	 */
	protected function getAvailableBrushes() {
		$options = array();

		$libraries = $this->brushDiscoveryService->getBrushes();
		$brushes = $libraries[$this->settings['library']];

		foreach ($brushes as $brushIdentifier => $brushLabel) {
			$brushAlias = $this->highlighterConfiguration->getBrushAliasByIdentifier($brushIdentifier);
			$options[$brushAlias] = $brushLabel;
		}

		return $options;
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Update\AbstractUpdate::execute()
	 */
	public function execute() {
		$this->view->assign('section', 'Execute');

		$successfulUpdates = 0;

		if ($this->hasUpdateInstruction('language')) {
			$newLanguages = GeneralUtility::_GP('language');

			foreach ($this->plugins as $plugin) {
				try {
					$updateResult = $this->updatePluginLanguage($plugin);

					if ($updateResult) {
						$successfulUpdates = $successfulUpdates + 1;
					}
				} catch (\TYPO3\CMS\Core\Exception $e) {
					continue;
				}
			}
		}

		$this->view->assign('totalUpdates', count($this->plugins));
		$this->view->assign('successfulUpdates', $successfulUpdates);

		return $this->view->render();
	}

	/**
	 * Updates the language (cLang) within the flexform of the given plugin
	 *
	 * @param array $plugin A tt_content record which reflects a beautyofcode plugin
	 * @throws \TYPO3\CMS\Core\Exception If the target plugin wasn't found in
	 *                                   REQUEST['language'] stack
	 * @return boolean TRUE if the update query was successful, false otherwise
	 */
	protected function updatePluginLanguage(array $plugin) {
		$newLanguages = GeneralUtility::_GP('language');

		if (FALSE === array_key_exists($plugin['uid'], $newLanguages)) {
			throw new \TYPO3\CMS\Core\Exception(
				'Plugin not found in incoming language request array.',
				1391374938
			);
		}

		$plugin['pi_flexform'] = ArrayUtility::setValueByPath(
			$plugin['pi_flexform'],
			'data/sDEF/lDEF/cLang/vDEF',
			$newLanguages[$plugin['uid']]
		);

		$flexformXml = $this->flexformTools->flexArray2Xml($plugin['pi_flexform'], TRUE);
		$flexformXml = str_replace('encoding=""', 'encoding="UTF-8"', $flexformXml);

		$updateResult = $this->db->exec_UPDATEquery(
			'tt_content',
			'uid=' . $plugin['uid'],
			array(
				'pi_flexform' => $flexformXml
			)
		);

		return $updateResult;
	}
}
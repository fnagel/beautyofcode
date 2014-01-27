<?php
namespace TYPO3\Beautyofcode\Update;

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

/**
 * The language setting update class
 *
 * @package \TYPO3\Beautyofcode\Update
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LanguageSetting extends \TYPO3\Beautyofcode\Update\AbstractUpdate {

	/**
	 * Stack of all plugins on this TYPO3.CMS instance
	 *
	 * @var array
	 */
	protected $plugins = array();

	/**
	 *
	 * @var strng
	 */
	protected $template = 'EXT:beautyofcode/Resources/Private/Templates/Update/LanguageSetting.html';

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\BrushDiscoveryService
	 */
	protected $brushDiscoveryService;

	/**
	 *
	 * @var \TYPO3\CMS\Core\Configuration\Flexform\FlexformTools
	 */
	protected $flexformTools;

	/**
	 *
	 * @param \TYPO3\Beautyofcode\Service\BrushDiscoveryService $brushDiscoveryService
	 * @return void
	 */
	public function injectBrushDiscoveryService(\TYPO3\Beautyofcode\Service\BrushDiscoveryService $brushDiscoveryService = NULL) {
		if (NULL === $brushDiscoveryService && NULL === $this->brushDiscoveryService) {
			$this->brushDiscoveryService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\Beautyofcode\\Service\\BrushDiscoveryService');
		} else if (NULL !== $brushDiscoveryService) {
			$this->brushDiscoveryService = $brushDiscoveryService;
		}
	}

	/**
	 *
	 * @param \TYPO3\CMS\Core\Configuration\Flexform\FlexformTools $flexformTools
	 * @return void
	 */
	public function injectFlexformTools(\TYPO3\CMS\Core\Configuration\Flexform\FlexformTools $flexformTools = NULL) {
		if (NULL === $flexformTools && NULL === $this->flexformTools) {
			$this->flexformTools = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools');
		} else if (NULL !== $flexformTools) {
			$this->flexformTools = $flexformTools;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\Beautyofcode\Update\AbstractUpdate::initializeObject()
	 */
	public function initializeObject() {
		$this->injectBrushDiscoveryService();
		$this->injectFlexformTools();

		$this->plugins = $this->db->exec_SELECTquery(
			'uid, header, pi_flexform',
			'tt_content',
			sprintf(
				'list_type IN (%s)',
				'\'' . implode('\',\'', array('beautyofcode_pi1', 'beautyofcode_contentrenderer')) . '\''
			)
		);

		$templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->template);
		$this->view->setTemplatePathAndFilename($templatePath);

		$this->expandFlexformData();
	}

	/**
	 * Expands the flexform data of all plugins within the plugin stack
	 *
	 * @return void
	 */
	protected function expandFlexformData() {
		$_plugins = array();

		while ($plugin = $this->db->sql_fetch_assoc($this->plugins)) {
			$flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($plugin['pi_flexform']);

			$plugin['pi_flexform'] = $flexformData;
			$plugin['header'] = $this->getPluginHeader($plugin);

			$_plugins[] = $plugin;
		}

		$this->db->sql_free_result($this->plugins);

		$this->plugins = $_plugins;
	}

	/**
	 * Returns the plugin header by first looking into the flexform (cLabel), then header field
	 *
	 * @param array $plugin An associative array of the tt_content plugin record
	 * @return string
	 */
	protected function getPluginHeader($plugin) {
		$cLabel = \TYPO3\CMS\Core\Utility\ArrayUtility::getValueByPath(
			$plugin['pi_flexform'],
			'data/sDEF/lDEF/cLabel/vDEF'
		);

		if ('' !== $cLabel) {
			$title = $cLabel;
		} else if ('' !== $plugin['header']) {
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

		$libraries = $this->brushDiscoveryService->discoverBrushes();

		foreach ($libraries as $library => $brushes) {
			foreach ($brushes as $brushName => $brushAlias) {
				$options[$library][$brushName] = $brushAlias;
			}
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
			$newLanguages = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('language');

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
	 * @throws \TYPO3\CMS\Core\Exception If the target plugin wasn't found with REQUEST['language'] stack
	 * @return boolean TRUE if the update query was successful, false otherwise
	 */
	protected function updatePluginLanguage(array $plugin) {
		$newLanguages = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('language');

		if (FALSE === array_key_exists($plugin['uid'], $newLanguages)) {
			throw new \TYPO3\CMS\Core\Exception('Plugin not found in incoming language request array.', 1391374938);
		}

		$plugin['pi_flexform'] = \TYPO3\CMS\Core\Utility\ArrayUtility::setValueByPath(
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
?>
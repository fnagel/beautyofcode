<?php
namespace TYPO3\Beautyofcode\Backend\Hooks;

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
 * Hook class for PageLayoutView hook `list_type_Info`
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Backend\Hooks
 */
class PageLayoutViewHooks {

	/**
	 *
	 * @var string
	 */
	const TEMPLATE = 'PageLayoutViewHooks/ExtensionSummary.html';

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Mvc\View\BackendStandaloneView
	 */
	protected $view;

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
	 * injectView
	 *
	 * @param \TYPO3\Beautyofcode\Mvc\View\BackendStandaloneView $view
	 * @return void
	 */
	public function injectView(\TYPO3\Beautyofcode\Mvc\View\BackendStandaloneView $view = NULL) {
		// @codeCoverageIgnoreStart
		if (is_null($view)) {
			$view = $this
				->objectManager
				->get('TYPO3\\Beautyofcode\\Mvc\\View\\BackendStandaloneView');
		}
		// @codeCoverageIgnoreEnd

		$this->view = $view;
		$this->view->setTemplatePathAndFilename(self::TEMPLATE);
	}

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array &$parameters Parameters for the hook:
	 *                           'pObj' => reference to \TYPO3\CMS\Backend\View\PageLayoutView
	 *                           'row' => $row,
	 *                           'infoArr' => $infoArr
	 * @param \TYPO3\CMS\Backend\View\PageLayoutView &$parentObject
	 * @return string Rendered output for PageLayoutView
	 */
	public function getExtensionSummary(
		array &$parameters = array(),
		\TYPO3\CMS\Backend\View\PageLayoutView &$parentObject
	) {
		$this->injectObjectManager($this->objectManager);
		$this->injectView($this->view);

		$result = '';

		if ($parameters['row']['list_type'] !== 'beautyofcode_contentrenderer') {
			return $result;
		}

		$flexformData = GeneralUtility::xml2array(
			$parameters['row']['pi_flexform']
		);

		if (!is_array($flexformData)) {
			return $result;
		}

		$this->view->assign('row', $parameters['row']);
		$this->view->assign('flexformData', $flexformData);

		$result = $this->view->render();

		return $result;
	}
}
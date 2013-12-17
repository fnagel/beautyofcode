<?php
namespace TYPO3\Beautyofcode\Controller;

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
 * The frontend plugin controller for the syntaxhighlighter
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository
	 */
	protected $flexformRepository;

	/**
	 *
	 * @var string
	 */
	protected $filePathBase = 'http://alexgorbatchev.com/';

	/**
	 *
	 * @var string
	 */
	protected $filePathScripts = 'pub/sh/current/scripts/';

	/**
	 *
	 * @var string
	 */
	protected $filePathStyles = 'pub/sh/current/styles/';

	/**
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository $flexformRepository
	 */
	public function injectFlexformRepository(\TYPO3\Beautyofcode\Domain\Repository\FlexformRepository $flexformRepository) {
		$this->flexformRepository = $flexformRepository;
	}

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::initializeAction()
	 */
	public function initializeAction() {
		if ('Standalone' === $this->settings['library']) {
			$this->setDefaultFileResourcePaths();
		}
	}

	protected function setDefaultFileResourcePaths() {
		$isBaseUrlSet = trim($this->settings['baseUrl']) !== '';
		$isStylePathSet = trim($this->settings['styles']) !== '';
		$isScriptPathSet = trim($this->settings['scripts']) !== '';

		$overridePaths = $isBaseUrlSet && $isStylePathSet && $isScriptPathSet;

		if ($overridePaths) {
			$this->settings['baseUrl'] = \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath(
				$this->settings['baseUrl']
			);
			$this->settings['scripts'] = trim($this->settings['scripts']);
			$this->settings['styles'] = trim($this->settings['styles']);
		} else {
			$this->settings['baseUrl'] = $this->filePathBase;
			$this->settings['scripts'] = $this->filePathScripts;
			$this->settings['styles'] = $this->filePathStyles;
		}

// 		$this->excludeAssetFromConcatenation = !\TYPO3\CMS\Core\Utility\GeneralUtility::isOnCurrentHost($this->filePathBase);
	}

	/**
	 *
	 * @return void
	 */
	public function renderAction() {
		$flexform = $this
			->flexformRepository
			->reconstituteByContentObject(
				$this->configurationManager->getContentObject()
			);
		$flexform->setBrushes($this->settings['brushes']);

		$this->view->assign('flexform', $flexform);
	}
}
?>
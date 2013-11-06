<?php
namespace FNagel\Beautyofcode\Controller;

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
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	 * @var \FNagel\Beautyofcode\Utility\GeneralUtility
	 */
	protected $bocGeneralUtility;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	 */
	protected $flexformService;

	/**
	 *
	 * @param \FNagel\Beautyofcode\Utility\GeneralUtility $generalUtility
	 */
	public function injectBeautyofcodeGeneralUtility(\FNagel\Beautyofcode\Utility\GeneralUtility $generalUtility) {
		$this->bocGeneralUtility = $generalUtility;
	}

	/**
	 *
	 * @param \TYPO3\CMS\Extbase\Service\FlexFormService $flexformService
	 */
	public function injectFlexformService(\TYPO3\CMS\Extbase\Service\FlexFormService $flexformService) {
		$this->flexformService = $flexformService;
	}

	public function initializeAction() {
		$this->typoscriptFrontendController = $GLOBALS['TSFE'];

		$this->pageRenderer = $this->typoscriptFrontendController->getPageRenderer();

		if ($this->settings['version'] === 'jquery') {
			$this->addJqueryLibraries();
		} else if ($this->settings['version'] === 'standalone') {
// 			$this->addStandaloneLibraries();
		}
	}

	/**
	 * adds the necessary jquery libraries to the TSFE page renderer
	 *
	 * @return void
	 */
	protected function addJqueryLibraries() {
		// please note only the jquery core js is included by t3jquery.
		// All other components added manually cause of more flexibility
		if (T3JQUERY === TRUE) {
			// add jQuery core by t3jquery extension
			tx_t3jquery::addJqJS();
		} else if ($this->settings['jquery']['addjQuery'] > 0) {
			$this->pageRenderer->addJsLibrary(
				"beautyofcode_jquery",
				$this
					->typoscriptFrontendController
					->tmpl
					->getFileName(
						"EXT:beautyofcode/Resources/Public/Javascript/vendor/jquery/jquery-1.3.2.min.js"
					)
			);
		}

		// add jquery.beautyOfCode.js
		$this->pageRenderer->addJsLibrary(
			"beautyofcode_boc",
			$this->bocGeneralUtility->makeAbsolutePath(trim($this->settings['jquery']['scriptUrl']))
		);

		$inlineAsset = $this->uriBuilder->reset()
			->setTargetPageType($targetPageType)
			->setUseCacheHash(TRUE)
			->setFormat('js')
			->setCreateAbsoluteUri(FALSE)
			->uriFor('render', array(), 'JqueryAsset', NULL, 'AssetRenderer');

		$compress = FALSE;
		$excludeFromConcatenation = TRUE;
		$this->pageRenderer->addJsFooterFile($inlineAsset, 'text/javascript', $oompress, FALSE, '', $excludeFromConcatenation);
	}

	public function renderAction() {
		$flexform = $this->configurationManager->getContentObject()->data['pi_flexform'];
		$flexformValues = $this->flexformService->convertFlexFormContentToArray($flexform);

		$this->view->assignMultiple(array(
			'version' => $this->settings['version'],
			'lang' => $flexformValues['cLang'],
			'label' => $flexformValues['cLabel'],
			'code' => $flexformValues['cCode'],
			'cssConfig' => '',
		));
	}
}
?>
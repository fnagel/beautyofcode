<?php
namespace FNagel\Beautyofcode\Controller;

class JqueryAssetController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	 * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $yposcriptFrontendController
	 * @return void
	 */
	public function injectTypoScriptFrontendController(\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $yposcriptFrontendController) {
		$this->typoscriptFrontendController = $typoscriptFrontendController;

		$this->pageRenderer = $this->typoscriptFrontendController->getPageRenderer();
	}

	/**
	 *
	 * @param \FNagel\Beautyofcode\Utility\GeneralUtility $generalUtility
	 */
	public function injectBeautyofcodeGeneralUtility(\FNagel\Beautyofcode\Utility\GeneralUtility $generalUtility) {
		$this->bocGeneralUtility = $generalUtility;
	}

	public function initializeAction() {
		// please note only the jquery core js is included by t3jquery.
		// All other components added manually cause of more flexibility
		if (T3JQUERY === TRUE) {
			// add jQuery core by t3jquery extension
			tx_t3jquery::addJqJS();
		} else if ($this->settings['jquery']['addjQuery'] > 0) {
			$this->pageRenderer->addJsLibrary(
				"beautyofcode_jquery",
				$this->typoscriptFrontendController->tmpl->getFileName("EXT:beautyofcode/Resources/Public/Javascript/vendor/jquery/jquery-1.3.2.min.js")
			);
		}

		// add jquery.beautyOfCode.js
		$this->pageRenderer->addJsLibrary(
			"beautyofcode_boc",
			$this->bocGeneralUtility->makeAbsolutePath(trim($this->settings['jquery']['scriptUrl']))
		);
	}

	public function renderAction() {
		$this->view->assign('jQvar', $this->settings['jQueryNoConflict'] ? "jQuery" : "$");
		$this->view->assign('jQuerySelector', (strlen(trim($this->settings['jQuerySelector'])) > 0) ? trim($this->settings['jQuerySelector']) . ' ' : FALSE;
	}
}
?>
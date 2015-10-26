<?php
namespace TYPO3\Beautyofcode\ViewHelpers\PageRenderer;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Adds javascript libraries to the page footer
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @author (c) 2014 Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\ViewHelpers\PageRenderer
 */
class AddJsFooterFileViewHelper extends \TYPO3\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper {

	/**
	 * TypoScriptFrontendController
	 *
	 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected $typoscriptFrontendController;

	/**
	 * (non-PHPdoc)
	 * @see \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper::initialize()
	 */
	public function initialize() {
		$this->typoscriptFrontendController = $GLOBALS['TSFE'];
	}

	/**
	 * Adds JS file to footer
	 *
	 * @param string $file File name
	 * @param string $type Content Type
	 * @param bool $compress TYPO3 compress flag
	 * @param bool $forceOnTop TYPO3 force-on-top flag
	 * @param string $allWrap TYPO3 allWrap configuration
	 * @param bool $excludeFromConcatenation TYPO3 excl. from concat. flag
	 * @param string $splitChar The char used to split the allWrap value
	 *
	 * @return void
	 */
	public function render($file, $type = 'text/javascript', $compress = TRUE, $forceOnTop = FALSE, $allWrap = '', $excludeFromConcatenation = FALSE, $splitChar = '|') {
		$this->pageRenderer->addJsFooterFile(
			$this->typoscriptFrontendController
				->tmpl
				->getFileName($file),
			$type,
			$compress,
			$forceOnTop,
			$allWrap,
			$excludeFromConcatenation,
			$splitChar
		);

		return NULL;
	}
}

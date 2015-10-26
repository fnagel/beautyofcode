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
 * Adds javascript inline code to the page footer
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @package \TYPO3\Beautyofcode\ViewHelpers\PageRenderer
 */
class AddJsFooterInlineCodeViewHelper extends \TYPO3\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper {

	/**
	 * Renders the view helper
	 *
	 * @param string $name Name of the inline block
	 * @param bool $compress TYPO3 compress flag
	 * @param bool $forceOnTop TYPO3 force-on-top flag
	 *
	 * @return NULL
	 */
	public function render($name, $compress = TRUE, $forceOnTop = FALSE) {
		$block = $this->renderChildren();

		$this->pageRenderer->addJsFooterInlineCode($name, $block, $compress, $forceOnTop);

		return NULL;
	}
}

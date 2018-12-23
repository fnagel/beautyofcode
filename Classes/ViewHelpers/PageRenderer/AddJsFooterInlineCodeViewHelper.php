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
 * Adds javascript inline code to the page footer.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class AddJsFooterInlineCodeViewHelper extends \TYPO3\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('name', 'string', 'Name of the library');
        $this->registerArgument('compress', 'bool', 'TYPO3 compress flag', false, true);
        $this->registerArgument('forceOnTop', 'bool', 'TYPO3 force-on-top flag', false, false);
    }

    /**
     * Renders the view helper.
     */
    public function render()
    {
        $this->pageRenderer->addJsFooterInlineCode(
            $this->arguments['name'],
            $this->renderChildren(),
            $this->arguments['compress'],
            $this->arguments['forceOnTop']
        );
    }
}

<?php

namespace FelixNagel\Beautyofcode\ViewHelpers\PageRenderer;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Adds javascript inline code to the page footer.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class AddJsFooterInlineCodeViewHelper extends \FelixNagel\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper
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

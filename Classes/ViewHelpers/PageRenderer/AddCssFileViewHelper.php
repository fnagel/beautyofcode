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
 * Adds a css file resources to the page.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class AddCssFileViewHelper extends \TYPO3\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('file', 'string', 'File reference');
        $this->registerArgument('rel', 'string', 'rel-attribute value', false, 'stylesheet');
        $this->registerArgument('media', 'string', 'Css media attribute value', false, 'all');
        $this->registerArgument('title', 'string', 'Title of the link element', false, '');
        $this->registerArgument('compress', 'bool', 'TYPO3 compress flag', false, true);
        $this->registerArgument('forceOnTop', 'bool', 'TYPO3 force-on-top flag', false, false);
        $this->registerArgument('allWrap', 'string', 'TYPO3 allWrap configuration', false, '');
        $this->registerArgument('excludeFromConcatenation', 'bool', 'TYPO3 excl. from concat. flag', false, false);
    }

    /**
     * Renders the view helper.
     */
    public function render()
    {
        $this->pageRenderer->addCssFile(
            $this->arguments['file'],
            $this->arguments['rel'],
            $this->arguments['media'],
            $this->arguments['title'],
            $this->arguments['compress'],
            $this->arguments['forceOnTop'],
            $this->arguments['allWrap'],
            $this->arguments['excludeFromConcatenation']
        );

        return;
    }
}

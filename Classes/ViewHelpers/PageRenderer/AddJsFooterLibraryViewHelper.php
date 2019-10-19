<?php

namespace FelixNagel\Beautyofcode\ViewHelpers\PageRenderer;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FilePathSanitizer;

/**
 * Adds javascript libraries to the page footer.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class AddJsFooterLibraryViewHelper extends \FelixNagel\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('name', 'string', 'Name of the library');
        $this->registerArgument('file', 'string', 'File reference');
        $this->registerArgument('type', 'string', 'Type attribute of the script tag', false, 'text/javascript');
        $this->registerArgument('compress', 'bool', 'TYPO3 compress flag', false, false);
        $this->registerArgument('forceOnTop', 'bool', 'TYPO3 force-on-top flag', false, false);
        $this->registerArgument('allWrap', 'string', 'TYPO3 allWrap configuration', false, '');
        $this->registerArgument('excludeFromConcatenation', 'bool', 'TYPO3 excl. from concat. flag', false, false);
    }

    /**
     * Renders the view helper.
     */
    public function render()
    {
        if (file_exists($this->arguments['file'])) {
            /** @var FilePathSanitizer $filePathSanitizer */
            $filePathSanitizer = GeneralUtility::makeInstance(FilePathSanitizer::class);

            $this->pageRenderer->addJsFooterLibrary(
                $this->arguments['name'],
                $filePathSanitizer->sanitize($this->arguments['file']),
                $this->arguments['type'],
                $this->arguments['compress'],
                $this->arguments['forceOnTop'],
                $this->arguments['allWrap'],
                $this->arguments['excludeFromConcatenation']
            );
        }
    }
}

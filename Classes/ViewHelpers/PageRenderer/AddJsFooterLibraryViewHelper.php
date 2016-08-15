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
 * Adds javascript libraries to the page footer.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class AddJsFooterLibraryViewHelper extends \TYPO3\Beautyofcode\Core\ViewHelper\AbstractPageRendererViewHelper
{
    /**
     * TypoScriptFrontendController.
     *
     * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected $typoscriptFrontendController;

    /**
     * (non-PHPdoc).
     *
     * @see \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper::initialize()
     */
    public function initialize()
    {
        $this->typoscriptFrontendController = $GLOBALS['TSFE'];
    }

    /**
     * Renders the view helper.
     *
     * @param string $name                     Name of the library
     * @param string $file                     File reference
     * @param string $type                     Type attribute of the script tag
     * @param bool   $compress                 TYPO3 compress flag
     * @param bool   $forceOnTop               TYPO3 force-on-top flag
     * @param string $allWrap                  TYPO3 allWrap configuration
     * @param bool   $excludeFromConcatenation TYPO3 excl. from concat. flag
     */
    public function render($name, $file, $type = 'text/javascript', $compress = false, $forceOnTop = false, $allWrap = '', $excludeFromConcatenation = false)
    {
        if (!file_exists($file)) {
            return;
        }

        $this->pageRenderer->addJsFooterLibrary(
            $name,
            $this->typoscriptFrontendController
                ->tmpl
                ->getFileName($file),
            $type,
            $compress,
            $forceOnTop,
            $allWrap,
            $excludeFromConcatenation
        );

        return;
    }
}

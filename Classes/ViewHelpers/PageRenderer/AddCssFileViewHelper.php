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
     * Renders the view helper.
     *
     * @param string $file                     File reference
     * @param string $rel                      rel-attribute value
     * @param string $media                    Css media attribute value
     * @param string $title                    Title of the link element
     * @param bool   $compress                 TYPO3 compress flag
     * @param bool   $forceOnTop               TYPO3 force-on-top flag
     * @param string $allWrap                  TYPO3 allWrap configuration
     * @param bool   $excludeFromConcatenation TYPO3 excl. from concat. flag
     */
    public function render($file, $rel = 'stylesheet', $media = 'all', $title = '', $compress = true, $forceOnTop = false, $allWrap = '', $excludeFromConcatenation = false)
    {
        $this->pageRenderer->addCssFile(
            $file,
            $rel,
            $media,
            $title,
            $compress,
            $forceOnTop,
            $allWrap,
            $excludeFromConcatenation
        );

        return;
    }
}

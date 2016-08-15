<?php

namespace TYPO3\Beautyofcode\ViewHelpers;

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
 * MakeAbsoluteViewHelper.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class MakeAbsoluteViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Renders the view helper.
     *
     * @param string $url The url
     *
     * @return string
     */
    public function render($url)
    {
        return \TYPO3\Beautyofcode\Utility\GeneralUtility::makeAbsolutePath($url);
    }
}

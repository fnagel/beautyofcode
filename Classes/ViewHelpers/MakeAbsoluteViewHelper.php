<?php

namespace FelixNagel\Beautyofcode\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use FelixNagel\Beautyofcode\Utility\GeneralUtility;

/**
 * MakeAbsoluteViewHelper.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class MakeAbsoluteViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('url', 'string', 'The url');
    }

    public function render()
    {
        $url = $this->arguments['url'];
        return GeneralUtility::makeAbsolutePath($url);
    }
}

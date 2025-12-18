<?php

namespace FelixNagel\Beautyofcode\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Core\Environment;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use FelixNagel\Beautyofcode\Utility\GeneralUtility;

/**
 * FileExistsViewHelper.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class FileExistsViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('file', 'string', 'Path to the file');
    }

    public function render()
    {
        return file_exists(Environment::getPublicPath().GeneralUtility::makeAbsolutePath($this->arguments['file']));
    }
}

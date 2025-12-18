<?php

namespace FelixNagel\Beautyofcode\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Fluid view helper around GeneralUtility::trimExplode().
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ExplodeViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'CSV list string', false, null);
        $this->registerArgument('delimiter', 'string', 'Delimiter, defaults to ,', false, ',');
        $this->registerArgument('removeEmptyValues', 'bool', 'Flags if empty values should be removed', false, false);
    }

    public function render()
    {
        $value = $this->arguments['value'];
        $delimiter = $this->arguments['delimiter'];
        $removeEmptyValues = $this->arguments['removeEmptyValues'];

        if (is_null($value)) {
            $value = $this->renderChildren();
        }

        return GeneralUtility::trimExplode($delimiter, $value, $removeEmptyValues);
    }
}

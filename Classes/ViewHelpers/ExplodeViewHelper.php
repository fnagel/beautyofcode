<?php

namespace FelixNagel\Beautyofcode\ViewHelpers;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Fluid view helper around GeneralUtility::trimExplode().
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ExplodeViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'string', 'CSV list string', false, null);
        $this->registerArgument('delimiter', 'string', 'Delimiter, defaults to ,', false, ',');
        $this->registerArgument('removeEmptyValues', 'bool', 'Flags if empty values should be removed', false, false);
    }

    /**
     * Renders the TypoScript object in the given TypoScript setup path.
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $value = $arguments['value'];
        $delimiter = $arguments['delimiter'];
        $removeEmptyValues = $arguments['removeEmptyValues'];
        if (true === is_null($value)) {
            $value = $renderChildrenClosure();
        }

        return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($delimiter, $value, $removeEmptyValues);
    }
}

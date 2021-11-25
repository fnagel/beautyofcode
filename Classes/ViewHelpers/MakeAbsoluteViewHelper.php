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
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use FelixNagel\Beautyofcode\Utility\GeneralUtility;

/**
 * MakeAbsoluteViewHelper.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class MakeAbsoluteViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('url', 'string', 'The url');
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
        $url = $arguments['url'];
        return GeneralUtility::makeAbsolutePath($url);
    }
}
